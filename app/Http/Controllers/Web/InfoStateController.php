<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subscription\Subscription;
use App\Models\Analytics\InfoState;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class InfoStateController extends Controller
{
    /**
     * Display Info States
     */
    public function index(Request $request): View
    {
        $query = InfoState::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            });
        }

        // Filter by key
        if ($request->filled('key')) {
            $query->where('key', 'like', "%{$request->get('key')}%");
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

        $infoStates = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get subscription names for display
        $subscriptionIds = $infoStates->pluck('key')->filter()->unique();
        $subscriptions = Subscription::whereIn('id', $subscriptionIds)->pluck('name', 'id')->toArray();

        return ViewFacade::make('info-state.index', [
            'infoStates' => $infoStates,
            'subscriptions' => $subscriptions,
            'filters' => $request->only(['search', 'key', 'created_at_from', 'created_at_to', 'updated_at_from', 'updated_at_to']),
        ]);
    }

    /**
     * Show Info State Details
     */
    public function show(InfoState $infoState): View
    {
        $subscription = null;
        if ($infoState->key) {
            $subscription = Subscription::find($infoState->key);
        }

        return ViewFacade::make('info-state.show', [
            'infoState' => $infoState,
            'subscription' => $subscription,
        ]);
    }

    /**
     * Delete Info State
     */
    public function destroy(InfoState $infoState): Response
    {
            $infoState->delete();

            return redirect()->route('info-state.index')
                ->with('success', 'Info state deleted successfully.');
    }

    /**
     * Export Info States to CSV
     */
    public function export(Request $request): Response
    {
            $query = InfoState::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('key', 'like', "%{$search}%")
                        ->orWhere('value', 'like', "%{$search}%");
                });
            }

            if ($request->filled('key')) {
                $query->where('key', 'like', "%{$request->get('key')}%");
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

            $infoStates = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Key', 'Value', 'Created At', 'Updated At'];

            foreach ($infoStates as $infoState) {
                $csvData[] = [
                    $infoState->id,
                    $infoState->key,
                    is_array($infoState->value) ? json_encode($infoState->value) : $infoState->value,
                    $infoState->created_at->format('Y-m-d H:i:s'),
                    $infoState->updated_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'info_states_'.now()->format('Y-m-d_H-i-s').'.csv';

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
