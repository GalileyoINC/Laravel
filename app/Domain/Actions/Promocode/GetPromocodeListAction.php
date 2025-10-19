<?php

declare(strict_types=1);

namespace App\Domain\Actions\Promocode;

use App\Models\Finance\Promocode;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetPromocodeListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Promocode::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('text', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', (int) $filters['is_active']);
        }
        if (! empty($filters['active_from_from'])) {
            $query->whereDate('active_from', '>=', $filters['active_from_from']);
        }
        if (! empty($filters['active_from_to'])) {
            $query->whereDate('active_from', '<=', $filters['active_from_to']);
        }
        if (! empty($filters['active_to_from'])) {
            $query->whereDate('active_to', '>=', $filters['active_to_from']);
        }
        if (! empty($filters['active_to_to'])) {
            $query->whereDate('active_to', '<=', $filters['active_to_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
