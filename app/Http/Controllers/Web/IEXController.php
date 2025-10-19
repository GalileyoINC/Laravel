<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\IEX\ExportIexWebhooksToCsvAction;
use App\Domain\Actions\IEX\ExportMarketstackIndexesToCsvAction;
use App\Domain\Actions\IEX\GetIexWebhookListAction;
use App\Domain\Actions\IEX\GetMarketstackIndexListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\IEX\Web\IexWebhookIndexRequest;
use App\Http\Requests\IEX\Web\MarketstackIndexRequest;
use App\Models\System\IexWebhook;
use App\Models\System\MarketstackIndx;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IEXController extends Controller
{
    public function __construct(
        private readonly GetIexWebhookListAction $getIexWebhookListAction,
        private readonly ExportIexWebhooksToCsvAction $exportIexWebhooksToCsvAction,
        private readonly GetMarketstackIndexListAction $getMarketstackIndexListAction,
        private readonly ExportMarketstackIndexesToCsvAction $exportMarketstackIndexesToCsvAction,
    ) {}

    /**
     * Display IEX Webhooks
     */
    public function webhooks(IexWebhookIndexRequest $request): View
    {
        $filters = $request->validated();
        $webhooks = $this->getIexWebhookListAction->execute($filters, 20);

        return ViewFacade::make('iex.webhooks', [
            'webhooks' => $webhooks,
            'filters' => $filters,
        ]);
    }

    /**
     * Show IEX Webhook details
     */
    public function showWebhook(IexWebhook $webhook): View
    {
        return ViewFacade::make('iex.webhook-show', [
            'webhook' => $webhook,
        ]);
    }

    /**
     * Display Marketstack Indexes
     */
    public function marketstack(MarketstackIndexRequest $request): View
    {
        $filters = $request->validated();
        $indexes = $this->getMarketstackIndexListAction->execute($filters, 20);

        return ViewFacade::make('iex.marketstack', [
            'indexes' => $indexes,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Marketstack Index details
     */
    public function showMarketstack(MarketstackIndx $index): View
    {
        return ViewFacade::make('iex.marketstack-show', [
            'index' => $index,
        ]);
    }

    /**
     * Show Marketstack Index edit form
     */
    public function editMarketstack(MarketstackIndx $index): View
    {
        return ViewFacade::make('iex.marketstack-edit', [
            'index' => $index,
        ]);
    }

    /**
     * Update Marketstack Index
     */
    public function updateMarketstack(Request $request, MarketstackIndx $index): Response
    {
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

        return redirect()->route('iex.marketstack')
            ->with('success', 'Marketstack Index updated successfully.');
    }

    /**
     * Create Marketstack Index
     */
    public function createMarketstack(): View
    {
        return ViewFacade::make('iex.marketstack-create');
    }

    /**
     * Store Marketstack Index
     */
    public function storeMarketstack(Request $request): Response
    {
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

        return redirect()->route('iex.marketstack')
            ->with('success', 'Marketstack Index created successfully.');
    }

    /**
     * Export IEX Webhooks to CSV
     */
    public function exportWebhooks(IexWebhookIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportIexWebhooksToCsvAction->execute($filters);
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
    }

    /**
     * Export Marketstack Indexes to CSV
     */
    public function exportMarketstack(MarketstackIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportMarketstackIndexesToCsvAction->execute($filters);
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
    }
}
