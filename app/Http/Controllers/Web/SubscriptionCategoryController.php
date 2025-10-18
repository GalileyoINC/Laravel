<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\Web\SubscriptionCategoryRequest;
use App\Models\Subscription\SubscriptionCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class SubscriptionCategoryController extends Controller
{
    /**
     * Display Subscription Categories
     */
    public function index(Request $request): View
    {
        $query = SubscriptionCategory::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }

        // Filter by parent
        if ($request->filled('id_parent')) {
            $query->where('id_parent', $request->get('id_parent'));
        }

        // Filter by position
        if ($request->filled('position_no')) {
            $query->where('position_no', $request->get('position_no'));
        }

        $subscriptionCategories = $query->orderBy('position_no', 'asc')->orderBy('id', 'asc')->paginate(20);

        return ViewFacade::make('subscription-category.index', [
            'subscriptionCategories' => $subscriptionCategories,
            'filters' => $request->only(['search', 'name', 'id_parent', 'position_no']),
        ]);
    }

    /**
     * Show Subscription Category Details
     */
    public function show(SubscriptionCategory $subscriptionCategory): View
    {
        return ViewFacade::make('subscription-category.show', [
            'subscriptionCategory' => $subscriptionCategory,
        ]);
    }

    /**
     * Show Subscription Category Edit Form
     */
    public function edit(SubscriptionCategory $subscriptionCategory): View
    {
        return ViewFacade::make('subscription-category.edit', [
            'subscriptionCategory' => $subscriptionCategory,
        ]);
    }

    /**
     * Update Subscription Category
     */
    public function update(SubscriptionCategoryRequest $request, SubscriptionCategory $subscriptionCategory): Response
    {
            $subscriptionCategory->update($request->validated());

            return redirect()->route('subscription.index', ['idCategory' => $subscriptionCategory->id])
                ->with('success', 'Subscription category updated successfully.');
    }

    /**
     * Export Subscription Categories to CSV
     */
    public function export(Request $request): Response
    {
            $query = SubscriptionCategory::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            if ($request->filled('name')) {
                $query->where('name', 'like', "%{$request->get('name')}%");
            }

            if ($request->filled('id_parent')) {
                $query->where('id_parent', $request->get('id_parent'));
            }

            if ($request->filled('position_no')) {
                $query->where('position_no', $request->get('position_no'));
            }

            $subscriptionCategories = $query->orderBy('position_no', 'asc')->orderBy('id', 'asc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Name', 'Parent ID', 'Position No'];

            foreach ($subscriptionCategories as $subscriptionCategory) {
                $csvData[] = [
                    $subscriptionCategory->id,
                    $subscriptionCategory->name,
                    $subscriptionCategory->id_parent,
                    $subscriptionCategory->position_no,
                ];
            }

            $filename = 'subscription_categories_'.now()->format('Y-m-d_H-i-s').'.csv';

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
