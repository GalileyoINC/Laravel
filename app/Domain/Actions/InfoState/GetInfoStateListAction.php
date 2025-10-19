<?php

declare(strict_types=1);

namespace App\Domain\Actions\InfoState;

use App\Models\Analytics\InfoState;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetInfoStateListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, InfoState>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = InfoState::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['key'])) {
            $query->where('key', 'like', "%{$filters['key']}%");
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }
        if (! empty($filters['updated_at_from'])) {
            $query->whereDate('updated_at', '>=', $filters['updated_at_from']);
        }
        if (! empty($filters['updated_at_to'])) {
            $query->whereDate('updated_at', '<=', $filters['updated_at_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
