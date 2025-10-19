<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Subscription\ExportSubscriptionsToCsvAction;
use App\Domain\Actions\Subscription\GetSubscriptionListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\Web\SubscriptionIndexRequest;
use App\Http\Requests\Subscription\Web\SubscriptionStoreRequest;
use App\Http\Requests\Subscription\Web\SubscriptionUpdateRequest;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionCategory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriptionController extends Controller
{
    public function __construct(
        private readonly GetSubscriptionListAction $getSubscriptionListAction,
        private readonly ExportSubscriptionsToCsvAction $exportSubscriptionsToCsvAction,
    ) {}

    /**
     * Display Subscriptions
     */
    public function index(SubscriptionIndexRequest $request): View
    {
        $filters = $request->validated();
        $categoryId = $filters['idCategory'] ?? null;

        // Get categories hierarchy
        $categories = SubscriptionCategory::orderBy('position_no')->orderBy('id')->get();
        $categoryHierarchy = [];
        foreach ($categories as $category) {
            $categoryHierarchy[$category->id_parent][] = $category;
        }

        // Get user statistics for active subscriptions
        $userStatistics = [];
        $activeSubscriptions = Subscription::where('is_active', true)->get();
        foreach ($activeSubscriptions as $subscription) {
            // Count active users (status = 1) attached to this subscription
            $userStatistics[$subscription->id] = $subscription->users()->where('user.status', 1)->count();
        }

        $subscriptions = $this->getSubscriptionListAction->execute($filters, 20);

        $selectedCategory = $categoryId ? SubscriptionCategory::find($categoryId) : null;
        $title = $selectedCategory ? $selectedCategory->name : 'News Category';

        return ViewFacade::make('subscription.index', [
            'subscriptions' => $subscriptions,
            'categories' => $categoryHierarchy,
            'userStatistics' => $userStatistics,
            'selectedCategory' => $selectedCategory,
            'title' => $title,
            'filters' => $filters,
        ]);
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        $categories = SubscriptionCategory::orderBy('name')->get();

        return ViewFacade::make('subscription.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store new Subscription
     */
    public function store(SubscriptionStoreRequest $request): Response
    {
        $validated = $request->validated();
        $subscription = new Subscription();
        $subscription->id_subscription_category = $validated['id_subscription_category'];
        $subscription->title = $validated['title'];
        $subscription->percent = $validated['percent'] ?? null;
        $subscription->alias = $validated['alias'] ?? null;
        $subscription->description = $validated['description'] ?? null;
        $subscription->is_custom = $validated['is_custom'] ?? false;
        $subscription->show_reactions = $validated['show_reactions'] ?? false;
        $subscription->show_comments = $validated['show_comments'] ?? false;

        // Handle image upload
        if ($request->hasFile('imageFile')) {
            $imagePath = $request->file('imageFile')->store('subscriptions', 'public');
            $subscription->image = $imagePath;
        }

        $subscription->save();

        return redirect()->route('subscription.index')
            ->with('success', 'Subscription created successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(Subscription $subscription): View
    {
        $categories = SubscriptionCategory::orderBy('name')->get();

        return ViewFacade::make('subscription.edit', [
            'subscription' => $subscription,
            'categories' => $categories,
        ]);
    }

    /**
     * Update Subscription
     */
    public function update(SubscriptionUpdateRequest $request, Subscription $subscription): Response
    {
        $validated = $request->validated();
        $subscription->id_subscription_category = $validated['id_subscription_category'];
        $subscription->title = $validated['title'];
        $subscription->percent = $validated['percent'] ?? null;
        $subscription->alias = $validated['alias'] ?? null;
        $subscription->description = $validated['description'] ?? null;
        $subscription->is_custom = $validated['is_custom'] ?? $subscription->is_custom;
        $subscription->show_reactions = $validated['show_reactions'] ?? $subscription->show_reactions;
        $subscription->show_comments = $validated['show_comments'] ?? $subscription->show_comments;

        // Handle image upload
        if ($request->hasFile('imageFile')) {
            // Delete old image if exists
            if ($subscription->image && Storage::disk('public')->exists($subscription->image)) {
                Storage::disk('public')->delete($subscription->image);
            }

            $imagePath = $request->file('imageFile')->store('subscriptions', 'public');
            $subscription->image = $imagePath;
        }

        $subscription->save();

        return redirect()->route('subscription.index')
            ->with('success', 'Subscription updated successfully.');
    }

    /**
     * Super Update (Modal form)
     */
    public function superUpdate(Subscription $subscription): View
    {
        $categories = SubscriptionCategory::orderBy('name')->get();

        return ViewFacade::make('subscription.super-update', [
            'subscription' => $subscription,
            'categories' => $categories,
        ]);
    }

    /**
     * Activate Subscription
     */
    public function activate(Subscription $subscription): Response
    {
        $subscription->update(['is_active' => true]);

        return redirect()->back()
            ->with('success', 'Subscription activated successfully.');
    }

    /**
     * Deactivate Subscription
     */
    public function deactivate(Subscription $subscription): Response
    {
        $subscription->update(['is_active' => false]);

        return redirect()->back()
            ->with('success', 'Subscription deactivated successfully.');
    }

    /**
     * Delete Subscription
     */
    public function destroy(Subscription $subscription): Response
    {
        // Delete image if exists
        if ($subscription->image && Storage::disk('public')->exists($subscription->image)) {
            Storage::disk('public')->delete($subscription->image);
        }

        $subscription->delete();

        return redirect()->route('subscription.index')
            ->with('success', 'Subscription deleted successfully.');
    }

    /**
     * Export Subscriptions to CSV
     */
    public function export(SubscriptionIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportSubscriptionsToCsvAction->execute($filters);
        $filename = 'subscriptions_'.now()->format('Y-m-d_H-i-s').'.csv';

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
