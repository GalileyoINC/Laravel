<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    /**
     * Display Subscriptions
     */
    public function index(Request $request): View
    {
        $categoryId = $request->get('idCategory');

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
            $userStatistics[$subscription->id] = $subscription->userSubscriptions()->where('is_active', true)->count();
        }

        $query = Subscription::query();

        if ($categoryId) {
            $query->where('id_subscription_category', $categoryId);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('alias', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }

        // Filter by custom status
        if ($request->filled('is_custom')) {
            $query->where('is_custom', $request->get('is_custom'));
        }

        // Filter by show reactions
        if ($request->filled('show_reactions')) {
            $query->where('show_reactions', $request->get('show_reactions'));
        }

        // Filter by show comments
        if ($request->filled('show_comments')) {
            $query->where('show_comments', $request->get('show_comments'));
        }

        // Filter by date range
        if ($request->filled('sended_at_from')) {
            $query->whereDate('sended_at', '>=', $request->get('sended_at_from'));
        }
        if ($request->filled('sended_at_to')) {
            $query->whereDate('sended_at', '<=', $request->get('sended_at_to'));
        }

        $subscriptions = $query->with(['influencerPage', 'influencer'])->orderBy('created_at', 'desc')->paginate(20);

        $selectedCategory = $categoryId ? SubscriptionCategory::find($categoryId) : null;
        $title = $selectedCategory ? $selectedCategory->name : 'News Category';

        return ViewFacade::make('web.subscription.index', [
            'subscriptions' => $subscriptions,
            'categories' => $categoryHierarchy,
            'userStatistics' => $userStatistics,
            'selectedCategory' => $selectedCategory,
            'title' => $title,
            'filters' => $request->only(['search', 'is_active', 'is_custom', 'show_reactions', 'show_comments', 'sended_at_from', 'sended_at_to']),
        ]);
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        $categories = SubscriptionCategory::orderBy('name')->get();

        return ViewFacade::make('web.subscription.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store new Subscription
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'id_subscription_category' => 'required|exists:subscription_categories,id',
            'title' => 'required|string|max:255',
            'percent' => 'nullable|numeric|min:0|max:100',
            'alias' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_custom' => 'boolean',
            'show_reactions' => 'boolean',
            'show_comments' => 'boolean',
            'imageFile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $subscription = new Subscription();
            $subscription->id_subscription_category = $request->get('id_subscription_category');
            $subscription->title = $request->get('title');
            $subscription->percent = $request->get('percent');
            $subscription->alias = $request->get('alias');
            $subscription->description = $request->get('description');
            $subscription->is_custom = $request->boolean('is_custom');
            $subscription->show_reactions = $request->boolean('show_reactions');
            $subscription->show_comments = $request->boolean('show_comments');

            // Handle image upload
            if ($request->hasFile('imageFile')) {
                $imagePath = $request->file('imageFile')->store('subscriptions', 'public');
                $subscription->image = $imagePath;
            }

            $subscription->save();

            return redirect()->route('web.subscription.index')
                ->with('success', 'Subscription created successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create subscription: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit(Subscription $subscription): View
    {
        $categories = SubscriptionCategory::orderBy('name')->get();

        return ViewFacade::make('web.subscription.edit', [
            'subscription' => $subscription,
            'categories' => $categories,
        ]);
    }

    /**
     * Update Subscription
     */
    public function update(Request $request, Subscription $subscription): Response
    {
        $request->validate([
            'id_subscription_category' => 'required|exists:subscription_categories,id',
            'title' => 'required|string|max:255',
            'percent' => 'nullable|numeric|min:0|max:100',
            'alias' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_custom' => 'boolean',
            'show_reactions' => 'boolean',
            'show_comments' => 'boolean',
            'imageFile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $subscription->id_subscription_category = $request->get('id_subscription_category');
            $subscription->title = $request->get('title');
            $subscription->percent = $request->get('percent');
            $subscription->alias = $request->get('alias');
            $subscription->description = $request->get('description');
            $subscription->is_custom = $request->boolean('is_custom');
            $subscription->show_reactions = $request->boolean('show_reactions');
            $subscription->show_comments = $request->boolean('show_comments');

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

            return redirect()->route('web.subscription.index')
                ->with('success', 'Subscription updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update subscription: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Super Update (Modal form)
     */
    public function superUpdate(Subscription $subscription): View
    {
        $categories = SubscriptionCategory::orderBy('name')->get();

        return ViewFacade::make('web.subscription.super-update', [
            'subscription' => $subscription,
            'categories' => $categories,
        ]);
    }

    /**
     * Activate Subscription
     */
    public function activate(Subscription $subscription): Response
    {
        try {
            $subscription->update(['is_active' => true]);

            return redirect()->back()
                ->with('success', 'Subscription activated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to activate subscription: '.$e->getMessage()]);
        }
    }

    /**
     * Deactivate Subscription
     */
    public function deactivate(Subscription $subscription): Response
    {
        try {
            $subscription->update(['is_active' => false]);

            return redirect()->back()
                ->with('success', 'Subscription deactivated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to deactivate subscription: '.$e->getMessage()]);
        }
    }

    /**
     * Delete Subscription
     */
    public function destroy(Subscription $subscription): Response
    {
        try {
            // Delete image if exists
            if ($subscription->image && Storage::disk('public')->exists($subscription->image)) {
                Storage::disk('public')->delete($subscription->image);
            }

            $subscription->delete();

            return redirect()->route('web.subscription.index')
                ->with('success', 'Subscription deleted successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete subscription: '.$e->getMessage()]);
        }
    }

    /**
     * Export Subscriptions to CSV
     */
    public function export(Request $request): Response
    {
        try {
            $query = Subscription::with(['influencerPage', 'influencer']);

            $categoryId = $request->get('idCategory');
            if ($categoryId) {
                $query->where('id_subscription_category', $categoryId);
            }

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('alias', 'like', "%{$search}%");
                });
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', $request->get('is_active'));
            }

            if ($request->filled('is_custom')) {
                $query->where('is_custom', $request->get('is_custom'));
            }

            if ($request->filled('show_reactions')) {
                $query->where('show_reactions', $request->get('show_reactions'));
            }

            if ($request->filled('show_comments')) {
                $query->where('show_comments', $request->get('show_comments'));
            }

            if ($request->filled('sended_at_from')) {
                $query->whereDate('sended_at', '>=', $request->get('sended_at_from'));
            }
            if ($request->filled('sended_at_to')) {
                $query->whereDate('sended_at', '<=', $request->get('sended_at_to'));
            }

            $subscriptions = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Category', 'Title', 'Description', 'Percent', 'Alias', 'Is Active', 'Is Custom', 'Show Reactions', 'Show Comments', 'Created At'];

            foreach ($subscriptions as $subscription) {
                $csvData[] = [
                    $subscription->id,
                    $subscription->subscriptionCategory->name ?? '',
                    $subscription->title,
                    $subscription->description,
                    $subscription->percent,
                    $subscription->alias,
                    $subscription->is_active ? 'Yes' : 'No',
                    $subscription->is_custom ? 'Yes' : 'No',
                    $subscription->show_reactions ? 'Yes' : 'No',
                    $subscription->show_comments ? 'Yes' : 'No',
                    $subscription->created_at->format('Y-m-d H:i:s'),
                ];
            }

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

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to export subscriptions: '.$e->getMessage()]);
        }
    }
}
