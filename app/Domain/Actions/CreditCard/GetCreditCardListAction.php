<?php

declare(strict_types=1);

namespace App\Domain\Actions\CreditCard;

use App\Models\Finance\CreditCard;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetCreditCardListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, CreditCard>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = CreditCard::with(['user']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('num', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['user_id'])) {
            $query->where('user_id', (int) $filters['user_id']);
        }

        if (! empty($filters['is_active'])) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
