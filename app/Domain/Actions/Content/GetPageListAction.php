<?php

declare(strict_types=1);

namespace App\Domain\Actions\Content;

use App\Models\Content\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetPageListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Page::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }
        if (isset($filters['status'])) {
            $query->where('status', (int) $filters['status']);
        }
        if (! empty($filters['createTimeRange'])) {
            $parts = explode(' - ', (string) $filters['createTimeRange']);
            if (count($parts) === 2) {
                $query->whereBetween('created_at', [
                    \Carbon\Carbon::parse($parts[0])->startOfDay(),
                    \Carbon\Carbon::parse($parts[1])->endOfDay(),
                ]);
            }
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
