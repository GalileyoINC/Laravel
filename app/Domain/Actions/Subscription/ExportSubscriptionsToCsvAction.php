<?php

declare(strict_types=1);

namespace App\Domain\Actions\Subscription;

use App\Models\Subscription\Subscription;

final class ExportSubscriptionsToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<list<mixed>>
     */
    public function execute(array $filters): array
    {
        $query = Subscription::with(['influencerPage', 'influencer', 'subscription_category']);

        if (! empty($filters['idCategory'])) {
            $query->where('id_subscription_category', (int) $filters['idCategory']);
        }
        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('alias', 'like', "%{$search}%");
            });
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', (int) $filters['is_active']);
        }
        if (isset($filters['is_custom'])) {
            $query->where('is_custom', (int) $filters['is_custom']);
        }
        if (isset($filters['show_reactions'])) {
            $query->where('show_reactions', (int) $filters['show_reactions']);
        }
        if (isset($filters['show_comments'])) {
            $query->where('show_comments', (int) $filters['show_comments']);
        }
        if (! empty($filters['sended_at_from'])) {
            $query->whereDate('sended_at', '>=', $filters['sended_at_from']);
        }
        if (! empty($filters['sended_at_to'])) {
            $query->whereDate('sended_at', '<=', $filters['sended_at_to']);
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Category', 'Title', 'Description', 'Percent', 'Alias', 'Is Active', 'Is Custom', 'Show Reactions', 'Show Comments', 'Sended At'];
        foreach ($items as $subscription) {
            $rows[] = [
                $subscription->id,
                $subscription->subscription_category->name ?? '',
                $subscription->title,
                $subscription->description,
                $subscription->percent,
                '', // alias property doesn't exist
                $subscription->is_active ? 'Yes' : 'No',
                $subscription->is_custom ? 'Yes' : 'No',
                $subscription->show_reactions ? 'Yes' : 'No',
                $subscription->show_comments ? 'Yes' : 'No',
                $subscription->sended_at ? $subscription->sended_at->format('Y-m-d H:i:s') : '',
            ];
        }

        return $rows;
    }
}
