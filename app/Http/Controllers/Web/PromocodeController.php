<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Promocode\ExportPromocodesToCsvAction;
use App\Domain\Actions\Promocode\GetPromocodeListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Promocode\Web\PromocodeIndexRequest;
use App\Http\Requests\Promocode\Web\PromocodeStoreRequest;
use App\Http\Requests\Promocode\Web\PromocodeUpdateRequest;
use App\Models\Finance\Promocode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PromocodeController extends Controller
{
    public function __construct(
        private readonly GetPromocodeListAction $getPromocodeListAction,
        private readonly ExportPromocodesToCsvAction $exportPromocodesToCsvAction,
    ) {}

    /**
     * Display Promocodes
     */
    public function index(PromocodeIndexRequest $request): View
    {
        $filters = $request->validated();
        $promocodes = $this->getPromocodeListAction->execute($filters, 20);

        return ViewFacade::make('promocode.index', [
            'promocodes' => $promocodes,
            'filters' => $filters,
        ]);
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        return ViewFacade::make('promocode.create');
    }

    /**
     * Store new Promocode
     */
    public function store(PromocodeStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $promocode = new Promocode();
        $promocode->type = $validated['type'];
        $promocode->text = $validated['text'];
        $promocode->discount = $validated['discount'] ?? 0;
        $promocode->trial_period = $validated['trial_period'] ?? 0;
        $promocode->active_from = $validated['active_from'];
        $promocode->active_to = $validated['active_to'];
        $promocode->is_active = $validated['is_active'] ?? true;
        $promocode->show_on_frontend = $validated['show_on_frontend'] ?? false;
        $promocode->description = $validated['description'] ?? null;

        $promocode->save();

        return redirect()->route('promocode.index')
            ->with('success', 'Promocode created successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(Promocode $promocode): View
    {
        return ViewFacade::make('promocode.edit', [
            'promocode' => $promocode,
        ]);
    }

    /**
     * Update Promocode
     */
    public function update(PromocodeUpdateRequest $request, Promocode $promocode): RedirectResponse
    {
        $validated = $request->validated();
        $promocode->type = $validated['type'];
        $promocode->text = $validated['text'];
        $promocode->discount = $validated['discount'] ?? 0;
        $promocode->trial_period = $validated['trial_period'] ?? 0;
        $promocode->active_from = $validated['active_from'];
        $promocode->active_to = $validated['active_to'];
        $promocode->is_active = $validated['is_active'] ?? $promocode->is_active;
        $promocode->show_on_frontend = $validated['show_on_frontend'] ?? $promocode->show_on_frontend;
        $promocode->description = $validated['description'] ?? null;

        $promocode->save();

        return redirect()->route('promocode.index')
            ->with('success', 'Promocode updated successfully.');
    }

    /**
     * Delete Promocode
     */
    public function destroy(Promocode $promocode): RedirectResponse
    {
        $promocode->delete();

        return redirect()->route('promocode.index')
            ->with('success', 'Promocode deleted successfully.');
    }

    /**
     * Export Promocodes to CSV
     */
    public function export(PromocodeIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportPromocodesToCsvAction->execute($filters);
        $filename = 'promocodes_'.now()->format('Y-m-d_H-i-s').'.csv';

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
