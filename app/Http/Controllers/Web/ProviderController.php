<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Provider\CreateProviderAction;
use App\Domain\Actions\Provider\ExportProvidersToCsvAction;
use App\Domain\Actions\Provider\GetProviderListAction;
use App\Domain\Actions\Provider\UpdateProviderAction;
use App\Domain\DTOs\Provider\ProviderCreateDTO;
use App\Domain\DTOs\Provider\ProviderUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\Web\ProviderRequest;
use App\Models\Finance\Provider;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProviderController extends Controller
{
    public function __construct(
        private readonly GetProviderListAction $getProviderListAction,
        private readonly ExportProvidersToCsvAction $exportProvidersToCsvAction,
        private readonly CreateProviderAction $createProviderAction,
        private readonly UpdateProviderAction $updateProviderAction,
    ) {}

    /**
     * Display Providers
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'name', 'email', 'is_satellite', 'country', 'created_at_from', 'created_at_to']);
        $providers = $this->getProviderListAction->execute($filters, 20);

        return ViewFacade::make('provider.index', [
            'providers' => $providers,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Provider Details
     */
    public function show(Provider $provider): View
    {
        return ViewFacade::make('provider.show', [
            'provider' => $provider,
        ]);
    }

    /**
     * Show Provider Create Form
     */
    public function create(): View
    {
        return ViewFacade::make('provider.create');
    }

    /**
     * Store Provider
     */
    public function store(ProviderRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $dto = new ProviderCreateDTO(
            name: $validated['name'],
            email: $validated['email'] ?? null,
            country: $validated['country'] ?? null,
            isSatellite: isset($validated['is_satellite']) ? (bool) $validated['is_satellite'] : null,
        );

        $this->createProviderAction->execute($dto);

        return redirect()->route('provider.index')
            ->with('success', 'Provider created successfully.');
    }

    /**
     * Show Provider Edit Form
     */
    public function edit(Provider $provider): View
    {
        return ViewFacade::make('provider.edit', [
            'provider' => $provider,
        ]);
    }

    /**
     * Update Provider
     */
    public function update(ProviderRequest $request, Provider $provider): RedirectResponse
    {
        $validated = $request->validated();
        $dto = new ProviderUpdateDTO(
            id: $provider->id,
            name: $validated['name'],
            email: $validated['email'] ?? null,
            country: $validated['country'] ?? null,
            isSatellite: isset($validated['is_satellite']) ? (bool) $validated['is_satellite'] : null,
        );

        $this->updateProviderAction->execute($dto);

        return redirect()->route('provider.show', $provider)
            ->with('success', 'Provider updated successfully.');
    }

    /**
     * Delete Provider
     */
    public function destroy(Provider $provider): RedirectResponse
    {
        $provider->delete();

        return redirect()->route('provider.index')
            ->with('success', 'Provider deleted successfully.');
    }

    /**
     * Export Providers to CSV
     */
    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'name', 'email', 'is_satellite', 'country', 'created_at_from', 'created_at_to']);
        $rows = $this->exportProvidersToCsvAction->execute($filters);
        $filename = 'providers_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
            }
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
