<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\System\IexWebhook;
use App\Models\System\MarketstackIndx;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class IEXController extends Controller
{
    /**
     * Display IEX Webhooks
     */
    public function webhooks(Request $request): View
    {
        $query = IexWebhook::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('iex_id', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhere('set', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filter by event
        if ($request->filled('event')) {
            $query->where('event', $request->get('event'));
        }

        // Filter by set
        if ($request->filled('set')) {
            $query->where('set', $request->get('set'));
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

        $webhooks = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('web.iex.webhooks', [
            'webhooks' => $webhooks,
            'filters' => $request->only(['search', 'event', 'set', 'created_at_from', 'created_at_to', 'updated_at_from', 'updated_at_to']),
        ]);
    }

    /**
     * Show IEX Webhook details
     */
    public function showWebhook(IexWebhook $webhook): View
    {
        return ViewFacade::make('web.iex.webhook-show', [
            'webhook' => $webhook,
        ]);
    }

    /**
     * Display Marketstack Indexes
     */
    public function marketstack(Request $request): View
    {
        $query = MarketstackIndx::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('symbol', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%")
                    ->orWhere('currency', 'like', "%{$search}%");
            });
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->where('country', $request->get('country'));
        }

        // Filter by currency
        if ($request->filled('currency')) {
            $query->where('currency', $request->get('currency'));
        }

        // Filter by has_intraday
        if ($request->filled('has_intraday')) {
            $query->where('has_intraday', $request->get('has_intraday'));
        }

        // Filter by has_eod
        if ($request->filled('has_eod')) {
            $query->where('has_eod', $request->get('has_eod'));
        }

        // Filter by is_active
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }

        $indexes = $query->orderBy('name')->paginate(20);

        return ViewFacade::make('web.iex.marketstack', [
            'indexes' => $indexes,
            'filters' => $request->only(['search', 'country', 'currency', 'has_intraday', 'has_eod', 'is_active']),
        ]);
    }

    /**
     * Show Marketstack Index details
     */
    public function showMarketstack(MarketstackIndx $index): View
    {
        return ViewFacade::make('web.iex.marketstack-show', [
            'index' => $index,
        ]);
    }

    /**
     * Show Marketstack Index edit form
     */
    public function editMarketstack(MarketstackIndx $index): View
    {
        return ViewFacade::make('web.iex.marketstack-edit', [
            'index' => $index,
        ]);
    }

    /**
     * Update Marketstack Index
     */
    public function updateMarketstack(Request $request, MarketstackIndx $index): Response
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'symbol' => 'required|string|max:10',
                'country' => 'required|string|max:100',
                'currency' => 'required|string|max:10',
                'has_intraday' => 'boolean',
                'has_eod' => 'boolean',
                'is_active' => 'boolean',
            ]);

            $index->update([
                'name' => $request->get('name'),
                'symbol' => $request->get('symbol'),
                'country' => $request->get('country'),
                'currency' => $request->get('currency'),
                'has_intraday' => $request->boolean('has_intraday'),
                'has_eod' => $request->boolean('has_eod'),
                'is_active' => $request->boolean('is_active'),
            ]);

            return redirect()->route('web.iex.marketstack')
                ->with('success', 'Marketstack Index updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update index: '.$e->getMessage()]);
        }
    }

    /**
     * Create Marketstack Index
     */
    public function createMarketstack(): View
    {
        return ViewFacade::make('web.iex.marketstack-create');
    }

    /**
     * Store Marketstack Index
     */
    public function storeMarketstack(Request $request): Response
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'symbol' => 'required|string|max:10',
                'country' => 'required|string|max:100',
                'currency' => 'required|string|max:10',
                'has_intraday' => 'boolean',
                'has_eod' => 'boolean',
                'is_active' => 'boolean',
            ]);

            MarketstackIndx::create([
                'name' => $request->get('name'),
                'symbol' => $request->get('symbol'),
                'country' => $request->get('country'),
                'currency' => $request->get('currency'),
                'has_intraday' => $request->boolean('has_intraday'),
                'has_eod' => $request->boolean('has_eod'),
                'is_active' => $request->boolean('is_active'),
            ]);

            return redirect()->route('web.iex.marketstack')
                ->with('success', 'Marketstack Index created successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create index: '.$e->getMessage()]);
        }
    }

    /**
     * Export IEX Webhooks to CSV
     */
    public function exportWebhooks(Request $request): Response
    {
        try {
            $query = IexWebhook::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('iex_id', 'like', "%{$search}%")
                        ->orWhere('event', 'like', "%{$search}%")
                        ->orWhere('set', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            }

            if ($request->filled('event')) {
                $query->where('event', $request->get('event'));
            }

            if ($request->filled('set')) {
                $query->where('set', $request->get('set'));
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $webhooks = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'IEX ID', 'Event', 'Set', 'Name', 'Created At', 'Updated At'];

            foreach ($webhooks as $webhook) {
                $csvData[] = [
                    $webhook->id,
                    $webhook->iex_id,
                    $webhook->event,
                    $webhook->set,
                    $webhook->name,
                    $webhook->created_at->format('Y-m-d H:i:s'),
                    $webhook->updated_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'iex_webhooks_'.now()->format('Y-m-d_H-i-s').'.csv';

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
                ->withErrors(['error' => 'Failed to export webhooks: '.$e->getMessage()]);
        }
    }

    /**
     * Export Marketstack Indexes to CSV
     */
    public function exportMarketstack(Request $request): Response
    {
        try {
            $query = MarketstackIndx::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('symbol', 'like', "%{$search}%")
                        ->orWhere('country', 'like', "%{$search}%")
                        ->orWhere('currency', 'like', "%{$search}%");
                });
            }

            if ($request->filled('country')) {
                $query->where('country', $request->get('country'));
            }

            if ($request->filled('currency')) {
                $query->where('currency', $request->get('currency'));
            }

            if ($request->filled('has_intraday')) {
                $query->where('has_intraday', $request->get('has_intraday'));
            }

            if ($request->filled('has_eod')) {
                $query->where('has_eod', $request->get('has_eod'));
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', $request->get('is_active'));
            }

            $indexes = $query->orderBy('name')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Name', 'Symbol', 'Country', 'Currency', 'Has Intraday', 'Has EOD', 'Is Active'];

            foreach ($indexes as $index) {
                $csvData[] = [
                    $index->id,
                    $index->name,
                    $index->symbol,
                    $index->country,
                    $index->currency,
                    $index->has_intraday ? 'Yes' : 'No',
                    $index->has_eod ? 'Yes' : 'No',
                    $index->is_active ? 'Yes' : 'No',
                ];
            }

            $filename = 'marketstack_indexes_'.now()->format('Y-m-d_H-i-s').'.csv';

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
                ->withErrors(['error' => 'Failed to export indexes: '.$e->getMessage()]);
        }
    }
}
