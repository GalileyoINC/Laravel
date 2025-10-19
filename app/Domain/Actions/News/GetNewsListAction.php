<?php

declare(strict_types=1);

namespace App\Domain\Actions\News;

use App\Models\Content\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetNewsListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = News::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }
        if (isset($filters['status'])) {
            $query->where('status', (int) $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
