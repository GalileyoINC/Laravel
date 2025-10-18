<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Communication\SmsPool;
use App\Models\Communication\SmsPoolArchive;
use App\Models\Subscription\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class SmsPoolArchiveController extends Controller
{
    /**
     * Display SMS Pool Archive
     */
    public function index(Request $request): View
    {
        $query = SmsPoolArchive::with(['user', 'staff', 'subscription', 'followerList']);

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

        $smsPoolArchives = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get dropdown data
        $purposes = SmsPool::getPurposes();
        $subscriptions = Subscription::getForDropDown();

        return ViewFacade::make('sms-pool-archive.index', [
            'smsPoolArchives' => $smsPoolArchives,
            'purposes' => $purposes,
            'subscriptions' => $subscriptions,
            'filters' => $request->only(['search', 'purpose', 'id_subscription', 'followerListName', 'created_at_from', 'created_at_to', 'updated_at_from', 'updated_at_to']),
        ]);
    }

    /**
     * Show SMS Pool Archive Details
     */
    public function show(SmsPoolArchive $smsPoolArchive): View
    {
        $smsPoolArchive->load(['user', 'staff', 'subscription', 'followerList']);

        return ViewFacade::make('sms-pool-archive.show', [
            'smsPoolArchive' => $smsPoolArchive,
        ]);
    }

    /**
     * Export SMS Pool Archive to CSV
     */
    public function export(Request $request): Response
    {
            $query = SmsPoolArchive::with(['user', 'staff', 'subscription', 'followerList']);

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

            if ($request->filled('id_subscription')) {
                $query->where('id_subscription', $request->get('id_subscription'));
            }

            if ($request->filled('followerListName')) {
                $query->whereHas('followerList', function ($followerListQuery) use ($request) {
                    $followerListQuery->where('name', 'like', "%{$request->get('followerListName')}%");
                });
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

            $smsPoolArchives = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Purpose', 'Subscription', 'Private Feed', 'Sender', 'Body', 'Created At', 'Updated At'];

            foreach ($smsPoolArchives as $smsPoolArchive) {
                $sender = '';
                if ($smsPoolArchive->user) {
                    $sender = 'User: '.$smsPoolArchive->user->first_name.' '.$smsPoolArchive->user->last_name;
                } elseif ($smsPoolArchive->staff) {
                    $sender = 'Staff: '.$smsPoolArchive->staff->username;
                }

                $csvData[] = [
                    $smsPoolArchive->id,
                    SmsPool::getPurposes()[$smsPoolArchive->purpose] ?? $smsPoolArchive->purpose,
                    $smsPoolArchive->subscription ? $smsPoolArchive->subscription->name : '',
                    $smsPoolArchive->followerList ? $smsPoolArchive->followerList->name : '',
                    $sender,
                    $smsPoolArchive->body,
                    $smsPoolArchive->created_at->format('Y-m-d H:i:s'),
                    $smsPoolArchive->updated_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'sms_pool_archive_'.now()->format('Y-m-d_H-i-s').'.csv';

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
