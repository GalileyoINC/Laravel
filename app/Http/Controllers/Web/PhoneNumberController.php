<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Communication\ExportPhoneNumbersToCsvAction;
use App\Domain\Actions\Communication\GetPhoneNumberListAction;
use App\Domain\Actions\Communication\SendSmsAction;
use App\Domain\DTOs\Communication\SendSmsRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Communication\Web\PhoneNumberIndexRequest;
use App\Http\Requests\Communication\Web\PhoneNumberRequest;
use App\Http\Requests\Communication\Web\PhoneNumberSuperRequest;
use App\Http\Requests\Communication\Web\SmsPhoneNumberRequest;
use App\Models\Device\PhoneNumber;
use App\Models\User\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View as ViewFacade;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PhoneNumberController extends Controller
{
    public function __construct(
        private readonly GetPhoneNumberListAction $getPhoneNumberListAction,
        private readonly ExportPhoneNumbersToCsvAction $exportPhoneNumbersToCsvAction,
        private readonly SendSmsAction $sendSmsAction,
    ) {}

    /**
     * Display Phone Numbers
     */
    public function index(PhoneNumberIndexRequest $request): View
    {
        $filters = $request->validated();
        $phoneNumbers = $this->getPhoneNumberListAction->execute($filters, 20);

        return ViewFacade::make('phone-number.index', [
            'phoneNumbers' => $phoneNumbers,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Phone Number Details
     */
    public function show(PhoneNumber $phoneNumber): View
    {
        return ViewFacade::make('phone-number.show', [
            'phoneNumber' => $phoneNumber,
        ]);
    }

    /**
     * Show Phone Number Edit Form
     */
    public function edit(PhoneNumber $phoneNumber): View
    {
        return ViewFacade::make('phone-number.edit', [
            'phoneNumber' => $phoneNumber,
        ]);
    }

    /**
     * Update Phone Number
     */
    public function update(PhoneNumberRequest $request, PhoneNumber $phoneNumber): RedirectResponse
    {
        $phoneNumber->update($request->validated());

        $redirectRoute = $request->input('referer') === 'user' ? 'user.index' : 'web.phone-number.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Phone number updated successfully.');
    }

    /**
     * Show Super Update Form (for super admin)
     */
    public function superUpdate(PhoneNumber $phoneNumber): View
    {
        // Check if user is super admin
        if (! Auth::user()?->isSuper()) {
            abort(403, 'Unauthorized access.');
        }

        return ViewFacade::make('phone-number.super-update', [
            'phoneNumber' => $phoneNumber,
        ]);
    }

    /**
     * Super Update Phone Number (for super admin)
     */
    public function superUpdateStore(PhoneNumberSuperRequest $request, PhoneNumber $phoneNumber): RedirectResponse
    {
        // Check if user is super admin
        if (! Auth::user()?->isSuper()) {
            abort(403, 'Unauthorized access.');
        }
        $phoneNumber->update($request->validated());

        return redirect()->route('phone-number.index')
            ->with('success', 'Phone number updated successfully.');
    }

    /**
     * Delete Phone Number (soft delete)
     */
    public function destroy(PhoneNumber $phoneNumber): RedirectResponse
    {
        $phoneNumber->update(['is_active' => false]);

        return redirect()->route('phone-number.index')
            ->with('success', 'Phone number deleted successfully.');
    }

    /**
     * Show Send SMS Form (for super admin)
     */
    public function sendSms(PhoneNumber $phoneNumber): View
    {
        // Check if user is super admin
        if (! Auth::user()?->isSuper()) {
            abort(403, 'Unauthorized access.');
        }

        return ViewFacade::make('phone-number.send-sms', [
            'phoneNumber' => $phoneNumber,
        ]);
    }

    /**
     * Send SMS to Phone Number (for super admin)
     */
    public function sendSmsStore(SmsPhoneNumberRequest $request, PhoneNumber $phoneNumber): RedirectResponse
    {
        // Check if user is super admin
        if (! Auth::user()?->isSuper()) {
            abort(403, 'Unauthorized access.');
        }
        $validated = $request->validated();

        $dto = new SendSmsRequestDTO(
            phoneNumberId: $phoneNumber->id,
            message: $validated['message'],
        );

        $this->sendSmsAction->execute($dto);

        return redirect()->route('phone-number.index')
            ->with('success', 'SMS sent successfully.');
    }

    /**
     * Create Phone Number for User
     */
    public function create(User $user): View
    {
        $phoneNumber = PhoneNumber::where('id_user', $user->id)->first();

        if (! $phoneNumber) {
            $phoneNumber = new PhoneNumber();
            $phoneNumber->id_user = $user->id;
        }

        if (! $phoneNumber->is_active) {
            $phoneNumber->number = '';
        }

        return ViewFacade::make('phone-number.create', [
            'phoneNumber' => $phoneNumber,
            'user' => $user,
        ]);
    }

    /**
     * Store Phone Number for User
     */
    public function store(PhoneNumberRequest $request, User $user): RedirectResponse
    {
        $phoneNumber = PhoneNumber::where('id_user', $user->id)->first();

        if (! $phoneNumber) {
            $phoneNumber = new PhoneNumber();
            $phoneNumber->id_user = $user->id;
        }

        $phoneNumber->fill($request->validated());
        $phoneNumber->save();

        return redirect()->route('user.index')
            ->with('success', 'Phone number created successfully.');
    }

    /**
     * Export Phone Numbers to CSV
     */
    public function export(PhoneNumberIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportPhoneNumbersToCsvAction->execute($filters);
        $filename = 'phone_numbers_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                throw new RuntimeException('Failed to open output stream');
            }
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
