<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\Web\ProviderRequest;
use App\Models\Finance\Provider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class ProviderController extends Controller
{
    /**
     * Display Providers
     */
    public function index(Request $request): View
    {
        $query = Provider::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            });
        }

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }

        // Filter by email
        if ($request->filled('email')) {
            $query->where('email', 'like', "%{$request->get('email')}%");
        }

        // Filter by is_satellite
        if ($request->filled('is_satellite')) {
            $query->where('is_satellite', $request->get('is_satellite'));
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->where('country', $request->get('country'));
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $providers = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('provider.index', [
            'providers' => $providers,
            'filters' => $request->only(['search', 'name', 'email', 'is_satellite', 'country', 'created_at_from', 'created_at_to']),
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
    public function store(ProviderRequest $request): Response
    {
            Provider::create($request->validated());

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
    public function update(ProviderRequest $request, Provider $provider): Response
    {
            $provider->update($request->validated());

            return redirect()->route('provider.show', $provider)
                ->with('success', 'Provider updated successfully.');
    }

    /**
     * Delete Provider
     */
    public function destroy(Provider $provider): Response
    {
            $provider->delete();

            return redirect()->route('provider.index')
                ->with('success', 'Provider deleted successfully.');
    }

    /**
     * Export Providers to CSV
     */
    public function export(Request $request): Response
    {
            $query = Provider::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('country', 'like', "%{$search}%");
                });
            }

            if ($request->filled('name')) {
                $query->where('name', 'like', "%{$request->get('name')}%");
            }

            if ($request->filled('email')) {
                $query->where('email', 'like', "%{$request->get('email')}%");
            }

            if ($request->filled('is_satellite')) {
                $query->where('is_satellite', $request->get('is_satellite'));
            }

            if ($request->filled('country')) {
                $query->where('country', $request->get('country'));
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $providers = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Name', 'Email', 'Is Satellite', 'Country', 'Created At', 'Updated At'];

            foreach ($providers as $provider) {
                $csvData[] = [
                    $provider->id,
                    $provider->name,
                    $provider->email,
                    $provider->is_satellite ? 'Yes' : 'No',
                    $provider->country,
                    $provider->created_at->format('Y-m-d H:i:s'),
                    $provider->updated_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'providers_'.now()->format('Y-m-d_H-i-s').'.csv';

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
