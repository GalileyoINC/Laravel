<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subscription\Follower;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class FollowerController extends Controller
{
    /**
     * Display Followers
     */
    public function index(Request $request): View
    {
        $query = Follower::with(['followerList', 'userLeader', 'userFollower']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('followerList', function ($followerListQuery) use ($search) {
                    $followerListQuery->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('userLeader', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('userFollower', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by follower list name
        if ($request->filled('followerListName')) {
            $query->whereHas('followerList', function ($followerListQuery) use ($request) {
                $followerListQuery->where('name', 'like', "%{$request->get('followerListName')}%");
            });
        }

        // Filter by user leader name
        if ($request->filled('userLeaderName')) {
            $query->whereHas('userLeader', function ($userQuery) use ($request) {
                $userQuery->where('first_name', 'like', "%{$request->get('userLeaderName')}%")
                    ->orWhere('last_name', 'like', "%{$request->get('userLeaderName')}%");
            });
        }

        // Filter by user follower name
        if ($request->filled('userFollowerName')) {
            $query->whereHas('userFollower', function ($userQuery) use ($request) {
                $userQuery->where('first_name', 'like', "%{$request->get('userFollowerName')}%")
                    ->orWhere('last_name', 'like', "%{$request->get('userFollowerName')}%");
            });
        }

        // Filter by is_active
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
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

        $followers = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('follower.index', [
            'followers' => $followers,
            'filters' => $request->only(['search', 'followerListName', 'userLeaderName', 'userFollowerName', 'is_active', 'created_at_from', 'created_at_to', 'updated_at_from', 'updated_at_to']),
        ]);
    }

    /**
     * Export Followers to CSV
     */
    public function export(Request $request): Response
    {
            $query = Follower::with(['followerList', 'userLeader', 'userFollower']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->whereHas('followerList', function ($followerListQuery) use ($search) {
                        $followerListQuery->where('name', 'like', "%{$search}%");
                    })
                        ->orWhereHas('userLeader', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('userFollower', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('followerListName')) {
                $query->whereHas('followerList', function ($followerListQuery) use ($request) {
                    $followerListQuery->where('name', 'like', "%{$request->get('followerListName')}%");
                });
            }

            if ($request->filled('userLeaderName')) {
                $query->whereHas('userLeader', function ($userQuery) use ($request) {
                    $userQuery->where('first_name', 'like', "%{$request->get('userLeaderName')}%")
                        ->orWhere('last_name', 'like', "%{$request->get('userLeaderName')}%");
                });
            }

            if ($request->filled('userFollowerName')) {
                $query->whereHas('userFollower', function ($userQuery) use ($request) {
                    $userQuery->where('first_name', 'like', "%{$request->get('userFollowerName')}%")
                        ->orWhere('last_name', 'like', "%{$request->get('userFollowerName')}%");
                });
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', $request->get('is_active'));
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

            $followers = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'List', 'Leader', 'Follower', 'Is Active', 'Created At', 'Updated At'];

            foreach ($followers as $follower) {
                $csvData[] = [
                    $follower->id,
                    $follower->followerList ? $follower->followerList->name : '',
                    $follower->userLeader ? $follower->userLeader->first_name.' '.$follower->userLeader->last_name : '',
                    $follower->userFollower ? $follower->userFollower->first_name.' '.$follower->userFollower->last_name : '',
                    $follower->is_active ? 'Yes' : 'No',
                    $follower->created_at->format('Y-m-d H:i:s'),
                    $follower->updated_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'followers_'.now()->format('Y-m-d_H-i-s').'.csv';

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
