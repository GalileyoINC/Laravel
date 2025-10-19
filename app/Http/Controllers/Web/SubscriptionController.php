<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Subscription\ActivateSubscriptionAction;
use App\Domain\Actions\Subscription\CreateSubscriptionAction;
use App\Domain\Actions\Subscription\DeactivateSubscriptionAction;
use App\Domain\Actions\Subscription\ExportSubscriptionsToCsvAction;
use App\Domain\Actions\Subscription\GetSubscriptionListAction;
use App\Domain\Actions\Subscription\UpdateSubscriptionAction;
use App\Domain\DTOs\Subscription\SubscriptionActivateRequestDTO;
use App\Domain\DTOs\Subscription\SubscriptionDeactivateRequestDTO;
use App\Domain\DTOs\Subscription\SubscriptionStoreRequestDTO;
use App\Domain\DTOs\Subscription\SubscriptionUpdateRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\Web\SubscriptionIndexRequest;
use App\Http\Requests\Subscription\Web\SubscriptionStoreRequest;
use App\Http\Requests\Subscription\Web\SubscriptionUpdateRequest;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriptionController extends Controller
{
    public function __construct(
        private readonly GetSubscriptionListAction $getSubscriptionListAction,
        private readonly ExportSubscriptionsToCsvAction $exportSubscriptionsToCsvAction,
        private readonly CreateSubscriptionAction $createSubscriptionAction,
        private readonly UpdateSubscriptionAction $updateSubscriptionAction,
        private readonly ActivateSubscriptionAction $activateSubscriptionAction,
        private readonly DeactivateSubscriptionAction $deactivateSubscriptionAction,
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
        $title = $selectedCategory ? (string) $selectedCategory->getAttribute('name') : 'News Category';

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
    public function store(SubscriptionStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $dto = new SubscriptionStoreRequestDTO(
            categoryId: (int) $validated['id_subscription_category'],
            title: $validated['title'],
            percent: $validated['percent'] ?? null,
            alias: $validated['alias'] ?? null,
            description: $validated['description'] ?? null,
            isCustom: (bool) ($validated['is_custom'] ?? false),
            showReactions: (bool) ($validated['show_reactions'] ?? false),
            showComments: (bool) ($validated['show_comments'] ?? false),
            imageFile: $request->file('imageFile'),
        );

        $this->createSubscriptionAction->execute($dto);

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
    public function update(SubscriptionUpdateRequest $request, Subscription $subscription): RedirectResponse
    {
        $validated = $request->validated();
        $dto = new SubscriptionUpdateRequestDTO(
            id: $subscription->id,
            categoryId: (int) $validated['id_subscription_category'],
            title: $validated['title'],
            percent: $validated['percent'] ?? null,
            alias: $validated['alias'] ?? null,
            description: $validated['description'] ?? null,
            isCustom: $validated['is_custom'] ?? null,
            showReactions: $validated['show_reactions'] ?? null,
            showComments: $validated['show_comments'] ?? null,
            imageFile: $request->file('imageFile'),
        );

        $this->updateSubscriptionAction->execute($dto);

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
    public function activate(Subscription $subscription): RedirectResponse
    {
        $dto = new SubscriptionActivateRequestDTO(id: $subscription->id);
        $this->activateSubscriptionAction->execute($dto);

        return redirect()->back()
            ->with('success', 'Subscription activated successfully.');
    }

    /**
     * Deactivate Subscription
     */
    public function deactivate(Subscription $subscription): RedirectResponse
    {
        $dto = new SubscriptionDeactivateRequestDTO(id: $subscription->id);
        $this->deactivateSubscriptionAction->execute($dto);

        return redirect()->back()
            ->with('success', 'Subscription deactivated successfully.');
    }

    /**
     * Delete Subscription
     */
    public function destroy(Subscription $subscription): RedirectResponse
    {
        // Delete image if exists
        $imagePath = $subscription->getAttribute('image');
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
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
