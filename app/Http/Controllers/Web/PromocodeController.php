<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Finance\Promocode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class PromocodeController extends Controller
{
    /**
     * Display Promocodes
     */
    public function index(Request $request): View
    {
        $query = Promocode::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('text', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }

        // Filter by date range
        if ($request->filled('active_from_from')) {
            $query->whereDate('active_from', '>=', $request->get('active_from_from'));
        }
        if ($request->filled('active_from_to')) {
            $query->whereDate('active_from', '<=', $request->get('active_from_to'));
        }

        if ($request->filled('active_to_from')) {
            $query->whereDate('active_to', '>=', $request->get('active_to_from'));
        }
        if ($request->filled('active_to_to')) {
            $query->whereDate('active_to', '<=', $request->get('active_to_to'));
        }

        $promocodes = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('promocode.index', [
            'promocodes' => $promocodes,
            'filters' => $request->only(['search', 'type', 'is_active', 'active_from_from', 'active_from_to', 'active_to_from', 'active_to_to']),
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
    public function store(Request $request): Response
    {
        $request->validate([
            'type' => 'required|string|in:discount,trial,influencer,test',
            'text' => 'required|string|max:255',
            'discount' => 'nullable|numeric|min:0|max:100',
            'trial_period' => 'nullable|integer|min:0',
            'active_from' => 'required|date',
            'active_to' => 'required|date|after:active_from',
            'is_active' => 'boolean',
            'show_on_frontend' => 'boolean',
            'description' => 'nullable|string',
        ]);
            $promocode = new Promocode();
            $promocode->type = $request->get('type');
            $promocode->text = $request->get('text');
            $promocode->discount = $request->get('discount', 0);
            $promocode->trial_period = $request->get('trial_period', 0);
            $promocode->active_from = $request->get('active_from');
            $promocode->active_to = $request->get('active_to');
            $promocode->is_active = $request->boolean('is_active', true);
            $promocode->show_on_frontend = $request->boolean('show_on_frontend', false);
            $promocode->description = $request->get('description');

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
    public function update(Request $request, Promocode $promocode): Response
    {
        $request->validate([
            'type' => 'required|string|in:discount,trial,influencer,test',
            'text' => 'required|string|max:255',
            'discount' => 'nullable|numeric|min:0|max:100',
            'trial_period' => 'nullable|integer|min:0',
            'active_from' => 'required|date',
            'active_to' => 'required|date|after:active_from',
            'is_active' => 'boolean',
            'show_on_frontend' => 'boolean',
            'description' => 'nullable|string',
        ]);
            $promocode->type = $request->get('type');
            $promocode->text = $request->get('text');
            $promocode->discount = $request->get('discount', 0);
            $promocode->trial_period = $request->get('trial_period', 0);
            $promocode->active_from = $request->get('active_from');
            $promocode->active_to = $request->get('active_to');
            $promocode->is_active = $request->boolean('is_active');
            $promocode->show_on_frontend = $request->boolean('show_on_frontend');
            $promocode->description = $request->get('description');

            $promocode->save();

            return redirect()->route('promocode.index')
                ->with('success', 'Promocode updated successfully.');
    }

    /**
     * Delete Promocode
     */
    public function destroy(Promocode $promocode): Response
    {
            $promocode->delete();

            return redirect()->route('promocode.index')
                ->with('success', 'Promocode deleted successfully.');
    }

    /**
     * Export Promocodes to CSV
     */
    public function export(Request $request): Response
    {
            $query = Promocode::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('text', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($request->filled('type')) {
                $query->where('type', $request->get('type'));
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', $request->get('is_active'));
            }

            if ($request->filled('active_from_from')) {
                $query->whereDate('active_from', '>=', $request->get('active_from_from'));
            }
            if ($request->filled('active_from_to')) {
                $query->whereDate('active_from', '<=', $request->get('active_from_to'));
            }

            if ($request->filled('active_to_from')) {
                $query->whereDate('active_to', '>=', $request->get('active_to_from'));
            }
            if ($request->filled('active_to_to')) {
                $query->whereDate('active_to', '<=', $request->get('active_to_to'));
            }

            $promocodes = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Type', 'Text', 'Discount', 'Trial Period', 'Active From', 'Active To', 'Is Active', 'Show on Frontend', 'Description', 'Created At'];

            foreach ($promocodes as $promocode) {
                $csvData[] = [
                    $promocode->id,
                    ucfirst((string) $promocode->type),
                    $promocode->text,
                    $promocode->discount,
                    $promocode->trial_period,
                    $promocode->active_from->format('Y-m-d'),
                    $promocode->active_to->format('Y-m-d'),
                    $promocode->is_active ? 'Yes' : 'No',
                    $promocode->show_on_frontend ? 'Yes' : 'No',
                    $promocode->description,
                    $promocode->created_at->format('Y-m-d H:i:s'),
                ];
            }

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
