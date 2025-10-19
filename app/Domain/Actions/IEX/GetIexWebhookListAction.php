<?php

declare(strict_types=1);

namespace App\Domain\Actions\IEX;

use App\Models\System\IexWebhook;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetIexWebhookListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, IexWebhook>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = IexWebhook::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('iex_id', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhere('set', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['event'])) {
            $query->where('event', $filters['event']);
        }
        if (! empty($filters['set'])) {
            $query->where('set', $filters['set']);
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
