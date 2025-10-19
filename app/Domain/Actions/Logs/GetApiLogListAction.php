<?php

declare(strict_types=1);

namespace App\Domain\Actions\Logs;

use App\Models\System\ApiLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetApiLogListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, ApiLog>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = ApiLog::query();

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

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
