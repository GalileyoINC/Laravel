<?php

declare(strict_types=1);

namespace App\Domain\Actions\Subscription;

use App\Models\Subscription\Subscription;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetSubscriptionListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Subscription::query();

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

        return $query->with(['influencerPage', 'influencer'])
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}
