<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Communication\SmsPool;
use App\Models\Communication\SmsSchedule;
use App\Models\Subscription\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class SmsScheduleController extends Controller
{
    /**
     * Display SMS Schedules
     */
    public function index(Request $request): View
    {
        $query = SmsSchedule::with(['user', 'staff', 'subscription', 'followerList', 'smsPool']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('body', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('staff', function ($staffQuery) use ($search) {
                        $staffQuery->where('username', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by purpose
        if ($request->filled('purpose')) {
            $query->where('purpose', $request->get('purpose'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by subscription
        if ($request->filled('id_subscription')) {
            $query->where('id_subscription', $request->get('id_subscription'));
        }

        // Filter by follower list
        if ($request->filled('followerListName')) {
            $query->whereHas('followerList', function ($followerListQuery) use ($request) {
                $followerListQuery->where('name', 'like', "%{$request->get('followerListName')}%");
            });
        }

        // Filter by date range
        if ($request->filled('sended_at_from')) {
            $query->whereDate('sended_at', '>=', $request->get('sended_at_from'));
        }
        if ($request->filled('sended_at_to')) {
            $query->whereDate('sended_at', '<=', $request->get('sended_at_to'));
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

        $smsSchedules = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get dropdown data
        $purposes = SmsPool::getPurposes();
        $statuses = SmsSchedule::getStatuses();
        $subscriptions = Subscription::getForDropDown();

        return ViewFacade::make('sms-schedule.index', [
            'smsSchedules' => $smsSchedules,
            'purposes' => $purposes,
            'statuses' => $statuses,
            'subscriptions' => $subscriptions,
            'filters' => $request->only(['search', 'purpose', 'status', 'id_subscription', 'followerListName', 'sended_at_from', 'sended_at_to', 'created_at_from', 'created_at_to', 'updated_at_from', 'updated_at_to']),
        ]);
    }

    /**
     * Show SMS Schedule Details
     */
    public function show(SmsSchedule $smsSchedule): View
    {
        $smsSchedule->load(['user', 'staff', 'subscription', 'followerList', 'smsPool']);

        return ViewFacade::make('sms-schedule.show', [
            'smsSchedule' => $smsSchedule,
        ]);
    }

    /**
     * Export SMS Schedules to CSV
     */
    public function export(Request $request): Response
    {
            $query = SmsSchedule::with(['user', 'staff', 'subscription', 'followerList', 'smsPool']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('body', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('staff', function ($staffQuery) use ($search) {
                            $staffQuery->where('username', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('purpose')) {
                $query->where('purpose', $request->get('purpose'));
            }

            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }

            if ($request->filled('id_subscription')) {
                $query->where('id_subscription', $request->get('id_subscription'));
            }

            if ($request->filled('followerListName')) {
                $query->whereHas('followerList', function ($followerListQuery) use ($request) {
                    $followerListQuery->where('name', 'like', "%{$request->get('followerListName')}%");
                });
            }

            if ($request->filled('sended_at_from')) {
                $query->whereDate('sended_at', '>=', $request->get('sended_at_from'));
            }
            if ($request->filled('sended_at_to')) {
                $query->whereDate('sended_at', '<=', $request->get('sended_at_to'));
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

            $smsSchedules = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Purpose', 'Sender', 'Subscription', 'Private Feed', 'Status', 'Body', 'Sended At', 'Created At', 'Updated At'];

            foreach ($smsSchedules as $smsSchedule) {
                $sender = '';
                if ($smsSchedule->user) {
                    $sender = 'User: '.$smsSchedule->user->first_name.' '.$smsSchedule->user->last_name;
                } elseif ($smsSchedule->staff) {
                    $sender = 'Staff: '.$smsSchedule->staff->username;
                }

                $csvData[] = [
                    $smsSchedule->id,
                    SmsPool::getPurposes()[$smsSchedule->purpose] ?? $smsSchedule->purpose,
                    $sender,
                    $smsSchedule->subscription ? $smsSchedule->subscription->name : '',
                    $smsSchedule->followerList ? $smsSchedule->followerList->name : '',
                    SmsSchedule::getStatuses()[$smsSchedule->status] ?? $smsSchedule->status,
                    $smsSchedule->body,
                    $smsSchedule->sended_at ? $smsSchedule->sended_at->format('Y-m-d H:i:s') : '',
                    $smsSchedule->created_at->format('Y-m-d H:i:s'),
                    $smsSchedule->updated_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'sms_schedules_'.now()->format('Y-m-d_H-i-s').'.csv';

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
}
