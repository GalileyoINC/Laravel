<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Communication\Web\PhoneNumberRequest;
use App\Http\Requests\Communication\Web\PhoneNumberSuperRequest;
use App\Http\Requests\Communication\Web\SmsPhoneNumberRequest;
use App\Models\Device\PhoneNumber;
use App\Models\Device\Provider;
use App\Models\User\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class PhoneNumberController extends Controller
{
    /**
     * Display Phone Numbers
     */
    public function index(Request $request): View
    {
        $query = PhoneNumber::with(['user', 'provider']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                    ->orWhere('twilio_type', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by user name
        if ($request->filled('userName')) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('first_name', 'like', "%{$request->get('userName')}%")
                    ->orWhere('last_name', 'like', "%{$request->get('userName')}%");
            });
        }

        // Filter by number
        if ($request->filled('number')) {
            $query->where('number', 'like', "%{$request->get('number')}%");
        }

        // Filter by is_valid
        if ($request->filled('is_valid')) {
            $query->where('is_valid', $request->get('is_valid'));
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        // Filter by provider
        if ($request->filled('id_provider')) {
            $query->where('id_provider', $request->get('id_provider'));
        }

        // Filter by twilio_type
        if ($request->filled('twilio_type')) {
            $query->where('twilio_type', $request->get('twilio_type'));
        }

        // Filter by is_active
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }

        // Filter by is_primary
        if ($request->filled('is_primary')) {
            $query->where('is_primary', $request->get('is_primary'));
        }

        // Filter by is_send
        if ($request->filled('is_send')) {
            $query->where('is_send', $request->get('is_send'));
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        if ($request->filled('updated_at_from')) {
            $query->whereDate('updated_at', '>=', $request->get('updated_at_from'));
        }
        if ($request->filled('updated_at_to')) {
            $query->whereDate('updated_at', '<=', $request->get('updated_at_to'));
        }

        $phoneNumbers = $query->orderBy('updated_at', 'desc')->paginate(20);

        return ViewFacade::make('web.phone-number.index', [
            'phoneNumbers' => $phoneNumbers,
            'filters' => $request->only(['search', 'userName', 'number', 'is_valid', 'type', 'id_provider', 'twilio_type', 'is_active', 'is_primary', 'is_send', 'created_at_from', 'created_at_to', 'updated_at_from', 'updated_at_to']),
        ]);
    }

    /**
     * Show Phone Number Details
     */
    public function show(PhoneNumber $phoneNumber): View
    {
        return ViewFacade::make('web.phone-number.show', [
            'phoneNumber' => $phoneNumber,
        ]);
    }

    /**
     * Show Phone Number Edit Form
     */
    public function edit(PhoneNumber $phoneNumber): View
    {
        return ViewFacade::make('web.phone-number.edit', [
            'phoneNumber' => $phoneNumber,
        ]);
    }

    /**
     * Update Phone Number
     */
    public function update(PhoneNumberRequest $request, PhoneNumber $phoneNumber): Response
    {
        try {
            $phoneNumber->update($request->validated());

            $redirectRoute = $request->get('referer') === 'user' ? 'web.user.index' : 'web.phone-number.index';

            return redirect()->route($redirectRoute)
                ->with('success', 'Phone number updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update phone number: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show Super Update Form (for super admin)
     */
    public function superUpdate(PhoneNumber $phoneNumber): View
    {
        // Check if user is super admin
        if (! auth()->user()->isSuper()) {
            abort(403, 'Unauthorized access.');
        }

        return ViewFacade::make('web.phone-number.super-update', [
            'phoneNumber' => $phoneNumber,
        ]);
    }

    /**
     * Super Update Phone Number (for super admin)
     */
    public function superUpdateStore(PhoneNumberSuperRequest $request, PhoneNumber $phoneNumber): Response
    {
        // Check if user is super admin
        if (! auth()->user()->isSuper()) {
            abort(403, 'Unauthorized access.');
        }

        try {
            $phoneNumber->update($request->validated());

            return redirect()->route('web.phone-number.index')
                ->with('success', 'Phone number updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update phone number: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Delete Phone Number (soft delete)
     */
    public function destroy(PhoneNumber $phoneNumber): Response
    {
        try {
            $phoneNumber->update(['is_active' => false]);

            return redirect()->route('web.phone-number.index')
                ->with('success', 'Phone number deleted successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete phone number: '.$e->getMessage()]);
        }
    }

    /**
     * Show Send SMS Form (for super admin)
     */
    public function sendSms(PhoneNumber $phoneNumber): View
    {
        // Check if user is super admin
        if (! auth()->user()->isSuper()) {
            abort(403, 'Unauthorized access.');
        }

        return ViewFacade::make('web.phone-number.send-sms', [
            'phoneNumber' => $phoneNumber,
        ]);
    }

    /**
     * Send SMS to Phone Number (for super admin)
     */
    public function sendSmsStore(SmsPhoneNumberRequest $request, PhoneNumber $phoneNumber): Response
    {
        // Check if user is super admin
        if (! auth()->user()->isSuper()) {
            abort(403, 'Unauthorized access.');
        }

        try {
            // Here you would implement the actual SMS sending logic
            // For now, we'll just simulate it

            $smsData = [
                'phone_number' => $phoneNumber,
                'message' => $request->get('message'),
            ];

            // Simulate SMS sending
            // SmsHelper::sendSms($smsData);

            return redirect()->route('web.phone-number.index')
                ->with('success', 'SMS sent successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to send SMS: '.$e->getMessage()])
                ->withInput();
        }
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

        return ViewFacade::make('web.phone-number.create', [
            'phoneNumber' => $phoneNumber,
            'user' => $user,
        ]);
    }

    /**
     * Store Phone Number for User
     */
    public function store(PhoneNumberRequest $request, User $user): Response
    {
        try {
            $phoneNumber = PhoneNumber::where('id_user', $user->id)->first();

            if (! $phoneNumber) {
                $phoneNumber = new PhoneNumber();
                $phoneNumber->id_user = $user->id;
            }

            $phoneNumber->fill($request->validated());
            $phoneNumber->save();

            return redirect()->route('web.user.index')
                ->with('success', 'Phone number created successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create phone number: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Export Phone Numbers to CSV
     */
    public function export(Request $request): Response
    {
        try {
            $query = PhoneNumber::with(['user', 'provider']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('number', 'like', "%{$search}%")
                        ->orWhere('twilio_type', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('userName')) {
                $query->whereHas('user', function ($userQuery) use ($request) {
                    $userQuery->where('first_name', 'like', "%{$request->get('userName')}%")
                        ->orWhere('last_name', 'like', "%{$request->get('userName')}%");
                });
            }

            if ($request->filled('number')) {
                $query->where('number', 'like', "%{$request->get('number')}%");
            }

            if ($request->filled('is_valid')) {
                $query->where('is_valid', $request->get('is_valid'));
            }

            if ($request->filled('type')) {
                $query->where('type', $request->get('type'));
            }

            if ($request->filled('id_provider')) {
                $query->where('id_provider', $request->get('id_provider'));
            }

            if ($request->filled('twilio_type')) {
                $query->where('twilio_type', $request->get('twilio_type'));
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', $request->get('is_active'));
            }

            if ($request->filled('is_primary')) {
                $query->where('is_primary', $request->get('is_primary'));
            }

            if ($request->filled('is_send')) {
                $query->where('is_send', $request->get('is_send'));
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            if ($request->filled('updated_at_from')) {
                $query->whereDate('updated_at', '>=', $request->get('updated_at_from'));
            }
            if ($request->filled('updated_at_to')) {
                $query->whereDate('updated_at', '<=', $request->get('updated_at_to'));
            }

            $phoneNumbers = $query->orderBy('updated_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'User', 'Email', 'Number', 'Valid', 'Type', 'Provider', 'Primary', 'Send', 'Created At', 'Updated At'];

            foreach ($phoneNumbers as $phoneNumber) {
                $csvData[] = [
                    $phoneNumber->id,
                    $phoneNumber->user ? $phoneNumber->user->first_name.' '.$phoneNumber->user->last_name : '',
                    $phoneNumber->user ? $phoneNumber->user->email : '',
                    $phoneNumber->number,
                    $phoneNumber->is_valid ? 'Yes' : 'No',
                    $phoneNumber->getTypeFilter()[$phoneNumber->type] ?? '',
                    $phoneNumber->provider ? $phoneNumber->provider->name : '',
                    $phoneNumber->is_primary ? 'Yes' : 'No',
                    $phoneNumber->is_send ? 'Yes' : 'No',
                    $phoneNumber->created_at->format('Y-m-d H:i:s'),
                    $phoneNumber->updated_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'sat_devices_'.now()->format('Y-m-d_H-i-s').'.csv';

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

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to export phone numbers: '.$e->getMessage()]);
        }
    }
}
