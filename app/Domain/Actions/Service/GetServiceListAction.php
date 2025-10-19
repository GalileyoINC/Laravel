<?php

declare(strict_types=1);

namespace App\Domain\Actions\Service;

use App\Models\Finance\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetServiceListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, mixed>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Service::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }
        if (! empty($filters['price_from'])) {
            $query->where('price', '>=', $filters['price_from']);
        }
        if (! empty($filters['price_to'])) {
            $query->where('price', '<=', $filters['price_to']);
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', (int) $filters['is_active']);
        }
        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
