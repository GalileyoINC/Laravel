<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\IEX\CreateMarketstackIndexAction;
use App\Domain\Actions\IEX\DeleteIexWebhookAction;
use App\Domain\Actions\IEX\ExportIexWebhooksToCsvAction;
use App\Domain\Actions\IEX\ExportMarketstackIndexesToCsvAction;
use App\Domain\Actions\IEX\GetIexWebhookListAction;
use App\Domain\Actions\IEX\GetMarketstackIndexListAction;
use App\Domain\Actions\IEX\UpdateMarketstackIndexAction;
use App\Domain\DTOs\IEX\MarketstackIndexStoreDTO;
use App\Domain\DTOs\IEX\MarketstackIndexUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\IEX\Web\IexWebhookIndexRequest;
use App\Http\Requests\IEX\Web\MarketstackIndexRequest;
use App\Http\Requests\IEX\Web\MarketstackStoreRequest;
use App\Http\Requests\IEX\Web\MarketstackUpdateRequest;
use App\Models\Finance\MarketstackIndx;
use App\Models\System\IexWebhook;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View as ViewFacade;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IEXController extends Controller
{
    public function __construct(
        private readonly GetIexWebhookListAction $getIexWebhookListAction,
        private readonly ExportIexWebhooksToCsvAction $exportIexWebhooksToCsvAction,
        private readonly DeleteIexWebhookAction $deleteIexWebhookAction,
        private readonly GetMarketstackIndexListAction $getMarketstackIndexListAction,
        private readonly ExportMarketstackIndexesToCsvAction $exportMarketstackIndexesToCsvAction,
        private readonly CreateMarketstackIndexAction $createMarketstackIndexAction,
        private readonly UpdateMarketstackIndexAction $updateMarketstackIndexAction,
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
     * Delete IEX Webhook
     */
    public function destroy(IexWebhook $webhook): RedirectResponse
    {
        $this->deleteIexWebhookAction->execute($webhook);

        return redirect()->route('iex.webhooks')
            ->with('success', 'Webhook deleted successfully.');
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
    public function updateMarketstack(MarketstackUpdateRequest $request, MarketstackIndx $index): RedirectResponse
    {
        $v = $request->validated();
        $dto = new MarketstackIndexUpdateDTO(
            id: $index->id,
            name: $v['name'],
            symbol: $v['symbol'],
            country: $v['country'],
            currency: $v['currency'],
            hasIntraday: (bool) ($v['has_intraday'] ?? false),
            hasEod: (bool) ($v['has_eod'] ?? false),
            isActive: (bool) ($v['is_active'] ?? false),
        );
        $this->updateMarketstackIndexAction->execute($dto);

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
    public function storeMarketstack(MarketstackStoreRequest $request): RedirectResponse
    {
        $v = $request->validated();
        $dto = new MarketstackIndexStoreDTO(
            name: $v['name'],
            symbol: $v['symbol'],
            country: $v['country'],
            currency: $v['currency'],
            hasIntraday: (bool) ($v['has_intraday'] ?? false),
            hasEod: (bool) ($v['has_eod'] ?? false),
            isActive: (bool) ($v['is_active'] ?? false),
        );
        $this->createMarketstackIndexAction->execute($dto);

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
