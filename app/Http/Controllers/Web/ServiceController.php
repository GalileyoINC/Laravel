<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Service\ExportServicesToCsvAction;
use App\Domain\Actions\Service\GetServiceListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Service\Web\ServiceIndexRequest;
use App\Http\Requests\Service\Web\ServiceRequest;
use App\Http\Requests\Service\Web\ServiceSettingsRequest;
use App\Models\Finance\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ServiceController extends Controller
{
    public function __construct(
        private readonly GetServiceListAction $getServiceListAction,
        private readonly ExportServicesToCsvAction $exportServicesToCsvAction,
    ) {}

    /**
     * Display Services
     */
    public function index(ServiceIndexRequest $request): View
    {
        $filters = $request->validated();
        $services = $this->getServiceListAction->execute($filters, 20);

        return ViewFacade::make('service.index', [
            'services' => $services,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Service Details
     */
    public function show(Service $service): View
    {
        return ViewFacade::make('service.show', [
            'service' => $service,
        ]);
    }

    /**
     * Show Service Create Form
     */
    public function create(Request $request): View
    {
        $type = $request->get('type', Service::TYPE_SUBSCRIBE);

        return ViewFacade::make('service.create', [
            'type' => $type,
        ]);
    }

    /**
     * Store Service
     */
    public function store(ServiceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['type'] = $request->get('type', Service::TYPE_SUBSCRIBE);
        $data['is_active'] = false;

        Service::create($data);

        return redirect()->route('service.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Show Service Edit Form
     */
    public function edit(Service $service): View
    {
        return ViewFacade::make('service.edit', [
            'service' => $service,
        ]);
    }

    /**
     * Update Service
     */
    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());

        return redirect()->route('service.show', $service)
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Show Service Settings Form
     */
    public function settings(): View
    {
        return ViewFacade::make('service.settings');
    }

    /**
     * Update Service Settings
     */
    public function settingsStore(ServiceSettingsRequest $request): RedirectResponse
    {
        // Here you would implement the actual settings saving logic
        // For now, we'll just simulate it

        $settings = $request->validated();

        // Simulate settings saving
        // Settings::updateServiceSettings($settings);

        return redirect()->route('service.index')
            ->with('success', 'Service settings updated successfully.');
    }

    /**
     * Show Custom Service Update Form
     */
    public function updateCustom(Service $service): View
    {
        return ViewFacade::make('service.update-custom', [
            'service' => $service,
        ]);
    }

    /**
     * Update Custom Service
     */
    public function updateCustomStore(ServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());

        return redirect()->route('service.index')
            ->with('success', 'Custom service updated successfully.');
    }

    /**
     * Export Services to CSV
     */
    public function export(ServiceIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportServicesToCsvAction->execute($filters);
        $filename = 'services_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
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
