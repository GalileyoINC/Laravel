<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\Web\ServiceRequest;
use App\Http\Requests\Service\Web\ServiceSettingsRequest;
use App\Models\Finance\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Display Services
     */
    public function index(Request $request): View
    {
        $query = Service::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }

        // Filter by price
        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->get('price_from'));
        }
        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->get('price_to'));
        }

        // Filter by is_active
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('web.service.index', [
            'services' => $services,
            'filters' => $request->only(['search', 'name', 'price_from', 'price_to', 'is_active', 'type']),
        ]);
    }

    /**
     * Show Service Details
     */
    public function show(Service $service): View
    {
        return ViewFacade::make('web.service.show', [
            'service' => $service,
        ]);
    }

    /**
     * Show Service Create Form
     */
    public function create(Request $request): View
    {
        $type = $request->get('type', Service::TYPE_SUBSCRIBE);

        return ViewFacade::make('web.service.create', [
            'type' => $type,
        ]);
    }

    /**
     * Store Service
     */
    public function store(ServiceRequest $request): Response
    {
        try {
            $data = $request->validated();
            $data['type'] = $request->get('type', Service::TYPE_SUBSCRIBE);
            $data['is_active'] = false;

            Service::create($data);

            return redirect()->route('web.service.index')
                ->with('success', 'Service created successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create service: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show Service Edit Form
     */
    public function edit(Service $service): View
    {
        return ViewFacade::make('web.service.edit', [
            'service' => $service,
        ]);
    }

    /**
     * Update Service
     */
    public function update(ServiceRequest $request, Service $service): Response
    {
        try {
            $service->update($request->validated());

            return redirect()->route('web.service.show', $service)
                ->with('success', 'Service updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update service: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show Service Settings Form
     */
    public function settings(): View
    {
        return ViewFacade::make('web.service.settings');
    }

    /**
     * Update Service Settings
     */
    public function settingsStore(ServiceSettingsRequest $request): Response
    {
        try {
            // Here you would implement the actual settings saving logic
            // For now, we'll just simulate it

            $settings = $request->validated();

            // Simulate settings saving
            // Settings::updateServiceSettings($settings);

            return redirect()->route('web.service.index')
                ->with('success', 'Service settings updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update service settings: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show Custom Service Update Form
     */
    public function updateCustom(Service $service): View
    {
        return ViewFacade::make('web.service.update-custom', [
            'service' => $service,
        ]);
    }

    /**
     * Update Custom Service
     */
    public function updateCustomStore(ServiceRequest $request, Service $service): Response
    {
        try {
            $service->update($request->validated());

            return redirect()->route('web.service.index')
                ->with('success', 'Custom service updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update custom service: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Export Services to CSV
     */
    public function export(Request $request): Response
    {
        try {
            $query = Service::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($request->filled('name')) {
                $query->where('name', 'like', "%{$request->get('name')}%");
            }

            if ($request->filled('price_from')) {
                $query->where('price', '>=', $request->get('price_from'));
            }
            if ($request->filled('price_to')) {
                $query->where('price', '<=', $request->get('price_to'));
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', $request->get('is_active'));
            }

            if ($request->filled('type')) {
                $query->where('type', $request->get('type'));
            }

            $services = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Name', 'Description', 'Price', 'Bonus Point', 'Type', 'Is Active', 'Created At', 'Updated At'];

            foreach ($services as $service) {
                $csvData[] = [
                    $service->id,
                    $service->name,
                    $service->description,
                    $service->price,
                    $service->bonus_point,
                    $service->type,
                    $service->is_active ? 'Yes' : 'No',
                    $service->created_at->format('Y-m-d H:i:s'),
                    $service->updated_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'services_'.now()->format('Y-m-d_H-i-s').'.csv';

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
                ->withErrors(['error' => 'Failed to export services: '.$e->getMessage()]);
        }
    }
}
