<?php

declare(strict_types=1);

namespace App\Domain\Actions\SubscriptionCategory;

use App\Models\Subscription\SubscriptionCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetSubscriptionCategoryListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
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

        return $query->orderBy('position_no', 'asc')->orderBy('id', 'asc')->paginate($perPage);
    }
}
