<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Users\ApplyUserCreditAction;
use App\Domain\Actions\Users\CreateUserAction;
use App\Domain\Actions\Users\DeleteUserAction;
use App\Domain\Actions\Users\ExportUsersToCsvAction;
use App\Domain\Actions\Users\GetUserDetailAction;
use App\Domain\Actions\Users\GetUsersListAction;
use App\Domain\Actions\Users\LoginAsUserAction;
use App\Domain\Actions\Users\RefuseInfluencerAction;
use App\Domain\Actions\Users\RemoveUserCreditAction;
use App\Domain\Actions\Users\SetSubscriptionFeedVisibilityAction;
use App\Domain\Actions\Users\ToggleUserStatusAction;
use App\Domain\Actions\Users\UpdateUserAction;
use App\Domain\DTOs\Users\ApplyUserCreditDTO;
use App\Domain\DTOs\Users\CreateUserDTO;
use App\Domain\DTOs\Users\ExportUsersRequestDTO;
use App\Domain\DTOs\Users\SetFeedVisibilityDTO;
use App\Domain\DTOs\Users\UpdateUserDTO;
use App\Domain\DTOs\Users\UsersListRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\Web\UserRequest;
use App\Models\Finance\ContractLine;
use App\Models\Finance\Invoice;
use App\Models\Finance\InvoiceLine;
use App\Models\Finance\Promocode;
use App\Models\User\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View as ViewFacade;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class UserController extends Controller
{
    public function __construct(
        private readonly CreateUserAction $createUserAction,
        private readonly GetUsersListAction $getUsersListAction,
        private readonly GetUserDetailAction $getUserDetailAction,
        private readonly UpdateUserAction $updateUserAction,
        private readonly DeleteUserAction $deleteUserAction,
        private readonly ToggleUserStatusAction $toggleUserStatusAction,
        private readonly LoginAsUserAction $loginAsUserAction,
        private readonly ExportUsersToCsvAction $exportUsersToCsvAction,
        private readonly SetSubscriptionFeedVisibilityAction $setSubscriptionFeedVisibilityAction,
        private readonly ApplyUserCreditAction $applyUserCreditAction,
        private readonly RemoveUserCreditAction $removeUserCreditAction,
        private readonly \App\Domain\Actions\Users\VerifyInfluencerAction $verifyInfluencerAction,
        private readonly RefuseInfluencerAction $refuseInfluencerAction,
    ) {}

    /**
     * Display a listing of users
     */
    public function index(Request $request): View
    {
        $dto = new UsersListRequestDTO(
            page: (int) $request->query('page', 1),
            pageSize: (int) $request->query('page_size', 20),
            search: $request->query('search'),
            role: $request->has('role') ? (int) $request->query('role') : null,
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
        $user = $this->getUserDetailAction->execute($user->id);

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

        $dto = new UpdateUserDTO(
            userId: $user->id,
            firstName: $validated['first_name'],
            lastName: $validated['last_name'],
            email: $validated['email'],
            country: $validated['country'],
            state: $validated['state'] ?? null,
            zip: $validated['zip'] ?? null,
            city: $validated['city'] ?? null,
            role: $validated['role'] ?? null,
            status: $validated['status'] ?? null,
            password: $validated['password'] ?? null,
        );

        $this->updateUserAction->execute($dto);

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

        $this->deleteUserAction->execute($user->id);

        return Redirect::to(route('user.index'))
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        $result = $this->toggleUserStatusAction->execute($user->id);
        $status = ((int) ($result['status'] ?? 0)) === 1 ? 'activated' : 'deactivated';

        return Redirect::back()
            ->with('success', "User {$status} successfully.");
    }

    /**
     * Login as user (super admin feature)
     */
    public function loginAsUser(User $user): RedirectResponse
    {
        try {
            $result = $this->loginAsUserAction->execute((int) Auth::id(), $user->id);
        } catch (Throwable $e) {
            return Redirect::back()->withErrors(['error' => $e->getMessage()]);
        }

        return Redirect::to('https://galileyo.com/super-login?snnd=vg_f43rr$433&t='.$result['token'])
            ->with('success', 'Logged in as user: '.$user->first_name.' '.$user->last_name);
    }

    /**
     * Export users to CSV
     */
    public function toCsv(Request $request): StreamedResponse
    {
        $dto = new ExportUsersRequestDTO(
            search: $request->get('search'),
            status: $request->has('status') ? (int) $request->get('status') : null,
            role: $request->has('role') ? (string) $request->get('role') : null,
            validEmailOnly: $request->boolean('valid_email_only', false),
        );

        $rows = $this->exportUsersToCsvAction->execute($dto);
        $filename = 'user_list_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
            }
            foreach ($rows as $row) {
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
    public function setFeedVisibility(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $subscriptionId = (int) $request->input('id');
            $checked = $request->input('checked') === 'true';

            $dto = new SetFeedVisibilityDTO(subscriptionId: $subscriptionId, isHidden: $checked);
            $result = $this->setSubscriptionFeedVisibilityAction->execute($dto);

            return response()->json(['success' => (bool) ($result['updated'] ?? false)]);
        }

        return response()->json(['success' => false], 400);
    }

    /**
     * Get transaction list for user
     */
    public function getTransactionList(User $user): JsonResponse
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
    public function getGatewayProfile(User $user): JsonResponse
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

        $dto = new ApplyUserCreditDTO(
            userId: $user->id,
            amount: (float) $request->input('amount'),
            reason: $request->input('reason', 'Admin credit'),
        );

        $this->applyUserCreditAction->execute($dto);

        return Redirect::route('user.show', $user)
            ->with('success', 'Credit applied successfully.');
    }

    /**
     * Remove credit from user
     */
    public function removeCredit(User $user): RedirectResponse
    {
        $this->removeUserCreditAction->execute($user->id);

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
        $this->verifyInfluencerAction->execute($user->id);

        return Redirect::route('user.index')
            ->with('success', 'Influencer verified successfully.');
    }

    /**
     * Refuse influencer
     */
    public function influencerRefused(User $user): RedirectResponse
    {
        $this->refuseInfluencerAction->execute($user->id);

        return Redirect::route('user.index')
            ->with('success', 'Influencer refused successfully.');
    }

    /**
     * Show terminate contract form
     */
    public function terminate(ContractLine $contractLine): View|RedirectResponse
    {
        if (request()->isMethod('post')) {
            $contractLine->terminated_at = now();
            $contractLine->save();

            return Redirect::route('user.show', $contractLine->id_user)
                ->with('success', 'Contract terminated successfully.');
        }

        return ViewFacade::make('user.terminate', [
            'contractLine' => $contractLine,
        ]);
    }
}
