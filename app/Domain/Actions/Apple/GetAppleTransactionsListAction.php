<?php

declare(strict_types=1);

namespace App\Domain\Actions\Apple;

use App\Models\Order\AppleAppTransaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetAppleTransactionsListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, AppleAppTransaction>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = AppleAppTransaction::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('error', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['is_process'])) {
            $query->where('is_process', (int) $filters['is_process']);
        }
        if (! empty($filters['id_user'])) {
            $query->where('id_user', (int) $filters['id_user']);
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
