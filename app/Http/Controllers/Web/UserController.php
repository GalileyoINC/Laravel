<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Users\CreateUserAction;
use App\Domain\Actions\Users\GetUsersListAction;
use App\Domain\DTOs\Users\CreateUserDTO;
use App\Domain\DTOs\Users\UsersListRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Web\UserRequest;
use App\Models\Finance\ContractLine;
use App\Models\Finance\Invoice;
use App\Models\Finance\InvoiceLine;
use App\Models\Finance\Promocode;
use App\Models\User\Subscription;
use App\Models\User\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly CreateUserAction $createUserAction,
        private readonly GetUsersListAction $getUsersListAction
    ) {}

    /**
     * Display a listing of users
     */
    public function index(Request $request): View
    {
        $dto = new UsersListRequestDTO(
            page: $request->get('page', 1),
            pageSize: $request->get('page_size', 20),
            search: $request->get('search'),
            role: $request->get('role'),
            validEmailOnly: $request->boolean('valid_email_only', false)
        );

        $users = $this->getUsersListAction->execute($dto->toArray());

        return ViewFacade::make('user.index', [
            'users' => $users,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new user
     */
    public function create(): View
    {
        return ViewFacade::make('user.create');
    }

    /**
     * Store a newly created user
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $dto = new CreateUserDTO(
            firstName: $validated['first_name'],
            lastName: $validated['last_name'],
            email: $validated['email'],
            password: $validated['password'],
            country: $validated['country'],
            zip: $validated['zip'] ?? null,
            state: $validated['state'] ?? null,
            city: $validated['city'] ?? null,
            role: $validated['role'] ?? 2,
            status: $validated['status'] ?? 1
        );

        $result = $this->createUserAction->execute($dto);

        if ($result->getData()->success) {
            return Redirect::to(route('user.index'))
                ->with('success', 'User created successfully.');
        }

        return Redirect::back()
            ->withErrors(['error' => $result->getData()->message ?? 'Failed to create user.'])
            ->withInput();
    }

    /**
     * Display the specified user
     */
    public function show(User $user): View
    {
        $user->load(['phoneNumbers', 'creditCards', 'subscriptions']);

        return ViewFacade::make('user.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user): View
    {
        return ViewFacade::make('user.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $data = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'country' => $validated['country'],
            'state' => $validated['state'] ?? null,
            'zip' => $validated['zip'] ?? null,
            'city' => $validated['city'] ?? null,
            'role' => $validated['role'] ?? $user->role,
            'status' => $validated['status'] ?? $user->status,
        ];

        if (! empty($validated['password'])) {
            $data['password_hash'] = bcrypt($validated['password']);
        }

        $user->update($data);

        return Redirect::to(route('user.index'))
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->role === 1) {
            return Redirect::back()
                ->withErrors(['error' => 'Cannot delete admin users.']);
        }

        $user->delete();

        return Redirect::to(route('user.index'))
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        $user->update([
            'status' => $user->status === 1 ? 0 : 1,
        ]);

        $status = $user->status === 1 ? 'activated' : 'deactivated';

        return Redirect::back()
            ->with('success', "User {$status} successfully.");
    }

    /**
     * Login as user (super admin feature)
     */
    public function loginAsUser(User $user): RedirectResponse
    {
        if (! Auth::user() || Auth::user()->role !== 1) {
            return Redirect::back()
                ->withErrors(['error' => 'Unauthorized action.']);
        }

        $user->admin_token = User::generatePasswordResetToken();
        $user->save();

        return Redirect::to('https://galileyo.com/super-login?snnd=vg_f43rr$433&t='.$user->admin_token)
            ->with('success', 'Logged in as user: '.$user->first_name.' '.$user->last_name);
    }

    /**
     * Export users to CSV
     */
    public function toCsv(Request $request): Response
    {
        $query = User::with(['phoneNumbers', 'subscriptionContractLines', 'preAdmin']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }

            $users = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['Id', 'First Name', 'Last Name', 'Contact', 'Email', 'Phone', 'Type', 'Valid', 'Status', 'Influencer', 'Test', 'Active Plan', 'Plan Type', 'Refer', 'Created At', 'SPS', 'State', 'Country', 'Zip'];

            foreach ($users as $user) {
                $phone = $user->phoneNumbers->first();
                $phoneNumber = $phone ? $phone->number : '';
                $phoneType = $phone ? $phone->getFullTypeName() : '';
                $isValid = $phone ? ($phone->is_valid ? 'Yes' : 'No') : '';

                $status = $user->status == User::STATUS_ACTIVE ? 'Active' : 'Cancelled';
                $influencer = $user->is_influencer ? 'Yes' : 'No';
                $test = $user->is_test ? 'Yes' : 'No';
                $spsActive = $user->is_sps_active ? 'Yes' : 'No';

                $activePlan = '';
                if ($user->preAdmin) {
                    $activePlan = 'Subaccount';
                } elseif ($user->subscriptionContractLines->isNotEmpty()) {
                    $activePlan = $user->subscriptionContractLines->pluck('title')->join('/');
                }

                $planType = '';
                if ($user->subscriptionContractLines->isNotEmpty()) {
                    $planType = $user->subscriptionContractLines->map(function ($line) {
                        return $line->getPayIntervalString();
                    })->join('/');
                }

                $csvData[] = [
                    $user->id,
                    $user->first_name,
                    $user->last_name,
                    '', // contact
                    $user->email,
                    $phoneNumber,
                    $phoneType,
                    $isValid,
                    $status,
                    $influencer,
                    $test,
                    $activePlan,
                    $planType,
                    $user->refer_type ?? '',
                    $user->created_at->format('Y-m-d H:i:s'),
                    $spsActive,
                    $user->state ?? '',
                    $user->country ?? '',
                    $user->zip ?? '',
                ];
            }

            $filename = 'user_list_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Set feed visibility (AJAX)
     */
    public function setFeedVisibility(Request $request): Response
    {
        if ($request->ajax()) {
            $subscriptionId = $request->input('id');
            $checked = $request->input('checked') === 'true';

            Subscription::where('id', $subscriptionId)
                ->update(['is_hidden' => $checked]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    /**
     * Get transaction list for user
     */
    public function getTransactionList(User $user): Response
    {
        $transactions = [];

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    /**
     * Get gateway profile for user
     */
    public function getGatewayProfile(User $user): Response
    {
        $profile = [];

        return response()->json([
            'success' => true,
            'data' => $profile,
        ]);
    }

    /**
     * Show credit form
     */
    public function credit(User $user): View
    {
        return ViewFacade::make('user.credit', [
            'user' => $user,
        ]);
    }

    /**
     * Apply credit to user
     */
    public function creditStore(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:255',
        ]);

        $amount = $request->input('amount');
        $reason = $request->input('reason', 'Admin credit');

        $user->bonus_point = ($user->bonus_point ?? 0) + $amount;
        $user->save();

        DB::table('user_point_history')->insert([
            'id_user' => $user->id,
            'point' => $amount,
            'reason' => $reason,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Redirect::route('user.show', $user)
            ->with('success', 'Credit applied successfully.');
    }

    /**
     * Remove credit from user
     */
    public function removeCredit(User $user): RedirectResponse
    {
        $user->bonus_point = 0;
        $user->save();

        return Redirect::route('user.show', $user)
            ->with('success', 'Credit removed successfully.');
    }

    /**
     * Show promocode management
     */
    public function promocode(): View
    {
        $promocodes = Promocode::with(['influencer'])
            ->where('type', Promocode::TYPE_SALE)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return ViewFacade::make('user.promocode', [
            'promocodes' => $promocodes,
        ]);
    }

    /**
     * Delete sale influencer promocode
     */
    public function deleteSaleInfluencerPromocode(Promocode $promocode): RedirectResponse
    {
            $promocode->delete();

            return Redirect::route('user.promocode')
                ->with('success', 'Promocode deleted successfully.');
    }

    /**
     * Get invoice line (AJAX)
     */
    public function getInvoiceLine(ContractLine $contractLine): View
    {
        $invoiceLines = InvoiceLine::with('invoice')
            ->where('id_contract_line', $contractLine->id)
            ->whereHas('invoice', function ($query) {
                $query->where('paid_status', Invoice::PAY_STATUS_SUCCESS);
            })
            ->get();

        return ViewFacade::make('user._contract_line_invoice', [
            'invoiceLines' => $invoiceLines,
        ]);
    }

    /**
     * Verify influencer
     */
    public function influencerVerified(User $user): RedirectResponse
    {
        $user->is_influencer = true;
        $user->influencer_verified_at = now();
        $user->save();

        return Redirect::route('user.index')
            ->with('success', 'Influencer verified successfully.');
    }

    /**
     * Refuse influencer
     */
    public function influencerRefused(User $user): RedirectResponse
    {
        $user->is_influencer = false;
        $user->influencer_verified_at = null;
        $user->save();

        return Redirect::route('user.index')
            ->with('success', 'Influencer refused successfully.');
    }

    /**
     * Show terminate contract form
     */
    public function terminate(ContractLine $contractLine): View|RedirectResponse
    {
        if (request()->isMethod('post')) {
            $contractLine->terminated_at = now()->format('Y-m-d');
            $contractLine->save();

            return Redirect::route('user.show', $contractLine->id_user)
                ->with('success', 'Contract terminated successfully.');
        }

        return ViewFacade::make('user.terminate', [
            'contractLine' => $contractLine,
        ]);
    }
}
