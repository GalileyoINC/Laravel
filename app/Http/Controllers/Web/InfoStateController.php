<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\InfoState\ExportInfoStatesToCsvAction;
use App\Domain\Actions\InfoState\GetInfoStateListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\InfoState\Web\InfoStateIndexRequest;
use App\Models\Analytics\InfoState;
use App\Models\Subscription\Subscription;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View as ViewFacade;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InfoStateController extends Controller
{
    public function __construct(
        private readonly GetInfoStateListAction $getInfoStateListAction,
        private readonly ExportInfoStatesToCsvAction $exportInfoStatesToCsvAction,
    ) {}

    /**
     * Display Info States
     */
    public function index(InfoStateIndexRequest $request): View
    {
        $filters = $request->validated();
        $infoStates = $this->getInfoStateListAction->execute($filters, 20);

        // Get subscription names for display
        $subscriptionIds = collect($infoStates->items())->pluck('key')->filter()->unique();
        $subscriptions = Subscription::whereIn('id', $subscriptionIds)->pluck('name', 'id')->toArray();

        return ViewFacade::make('info-state.index', [
            'infoStates' => $infoStates,
            'subscriptions' => $subscriptions,
            'filters' => $filters,
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
    public function destroy(InfoState $infoState): RedirectResponse
    {
        $infoState->delete();

        return redirect()->route('info-state.index')
            ->with('success', 'Info state deleted successfully.');
    }

    /**
     * Export Info States to CSV
     */
    public function export(InfoStateIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportInfoStatesToCsvAction->execute($filters);
        $filename = 'info_states_'.now()->format('Y-m-d_H-i-s').'.csv';

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
