<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subscription\FollowerList;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class FollowerListController extends Controller
{
    /**
     * Display Follower Lists
     */
    public function index(Request $request): View
    {
        $query = FollowerList::with(['user']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }

        // Filter by user name
        if ($request->filled('userName')) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('first_name', 'like', "%{$request->get('userName')}%")
                    ->orWhere('last_name', 'like', "%{$request->get('userName')}%");
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

        $followerLists = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('web.follower-list.index', [
            'followerLists' => $followerLists,
            'filters' => $request->only(['search', 'name', 'userName', 'is_active', 'created_at_from', 'created_at_to', 'updated_at_from', 'updated_at_to']),
        ]);
    }

    /**
     * Show Follower List Details
     */
    public function show(FollowerList $followerList): View
    {
        $followerList->load(['user']);

        return ViewFacade::make('web.follower-list.show', [
            'followerList' => $followerList,
        ]);
    }

    /**
     * Export Follower Lists to CSV
     */
    public function export(Request $request): Response
    {
        try {
            $query = FollowerList::with(['user']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('name')) {
                $query->where('name', 'like', "%{$request->get('name')}%");
            }

            if ($request->filled('userName')) {
                $query->whereHas('user', function ($userQuery) use ($request) {
                    $userQuery->where('first_name', 'like', "%{$request->get('userName')}%")
                        ->orWhere('last_name', 'like', "%{$request->get('userName')}%");
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

            $followerLists = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Name', 'User', 'Is Active', 'Created At', 'Updated At'];

            foreach ($followerLists as $followerList) {
                $csvData[] = [
                    $followerList->id,
                    $followerList->name,
                    $followerList->user ? $followerList->user->first_name.' '.$followerList->user->last_name : '',
                    $followerList->is_active ? 'Yes' : 'No',
                    $followerList->created_at->format('Y-m-d H:i:s'),
                    $followerList->updated_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'follower_lists_'.now()->format('Y-m-d_H-i-s').'.csv';

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
                ->withErrors(['error' => 'Failed to export follower lists: '.$e->getMessage()]);
        }
    }
}
