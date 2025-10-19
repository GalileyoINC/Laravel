<?php

declare(strict_types=1);

namespace App\Domain\Actions\SmsPool;

use App\Models\Communication\SmsPool;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetSmsPoolListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, mixed>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = SmsPool::with(['user', 'staff', 'subscription']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('body', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['purpose'])) {
            $query->where('purpose', $filters['purpose']);
        }
        if (! empty($filters['id_subscription'])) {
            $query->where('id_subscription', (int) $filters['id_subscription']);
        }
        if (! empty($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
