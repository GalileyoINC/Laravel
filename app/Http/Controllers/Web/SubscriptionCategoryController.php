<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\SubscriptionCategory\ExportSubscriptionCategoriesToCsvAction;
use App\Domain\Actions\SubscriptionCategory\GetSubscriptionCategoryListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\Web\SubscriptionCategoryRequest;
use App\Models\Subscription\SubscriptionCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriptionCategoryController extends Controller
{
    public function __construct(
        private readonly GetSubscriptionCategoryListAction $getSubscriptionCategoryListAction,
        private readonly ExportSubscriptionCategoriesToCsvAction $exportSubscriptionCategoriesToCsvAction,
    ) {}

    /**
     * Display Subscription Categories
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'name', 'id_parent', 'position_no']);
        $subscriptionCategories = $this->getSubscriptionCategoryListAction->execute($filters, 20);

        return ViewFacade::make('subscription-category.index', [
            'subscriptionCategories' => $subscriptionCategories,
            'filters' => $filters,
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
    public function update(SubscriptionCategoryRequest $request, SubscriptionCategory $subscriptionCategory): RedirectResponse
    {
        $subscriptionCategory->update($request->validated());

        return redirect()->route('subscription.index', ['idCategory' => $subscriptionCategory->id])
            ->with('success', 'Subscription category updated successfully.');
    }

    /**
     * Export Subscription Categories to CSV
     */
    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'name', 'id_parent', 'position_no']);
        $rows = $this->exportSubscriptionCategoriesToCsvAction->execute($filters);

        $filename = 'subscription_categories_'.now()->format('Y-m-d_H-i-s').'.csv';

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
