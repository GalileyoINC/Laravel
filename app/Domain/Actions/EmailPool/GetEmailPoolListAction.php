<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailPool;

use App\Models\Communication\EmailPool;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetEmailPoolListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, EmailPool>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = EmailPool::with(['attachments']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('from', 'like', "%{$search}%")
                    ->orWhere('to', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['type'])) {
            $query->where('type', (int) $filters['type']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', (int) $filters['status']);
        }

        if (! empty($filters['to'])) {
            $query->where('to', 'like', "%{$filters['to']}%");
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
