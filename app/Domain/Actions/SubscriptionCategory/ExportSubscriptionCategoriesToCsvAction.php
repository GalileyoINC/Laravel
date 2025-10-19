<?php

declare(strict_types=1);

namespace App\Domain\Actions\SubscriptionCategory;

use App\Models\Subscription\SubscriptionCategory;

final class ExportSubscriptionCategoriesToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<int, array<int, mixed>>
     */
    public function execute(array $filters): array
    {
        $query = SubscriptionCategory::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }
        if (isset($filters['id_parent'])) {
            $query->where('id_parent', (int) $filters['id_parent']);
        }
        if (isset($filters['position_no'])) {
            $query->where('position_no', (int) $filters['position_no']);
        }

        $subscriptionCategories = $query->orderBy('position_no', 'asc')->orderBy('id', 'asc')->get();

        $rows = [];
        $rows[] = ['ID', 'Name', 'Parent ID', 'Position No'];
        foreach ($subscriptionCategories as $subscriptionCategory) {
            $rows[] = [
                $subscriptionCategory->id,
                $subscriptionCategory->name,
                $subscriptionCategory->id_parent,
                $subscriptionCategory->position_no,
            ];
        }

        return $rows;
    }
}
