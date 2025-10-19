<?php

declare(strict_types=1);

namespace App\Domain\Actions\Finance;

use App\Models\Finance\MoneyTransaction;

final class GetMoneyTransactionListAction
{
    public function execute(array $filters, int $perPage = 20): array
    {
        $query = MoneyTransaction::with(['user', 'invoice', 'creditCard']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }
        if (! empty($filters['transaction_type'])) {
            $query->where('transaction_type', $filters['transaction_type']);
        }
        foreach (['is_success', 'is_void', 'is_test'] as $flag) {
            if (isset($filters[$flag])) {
                $query->where($flag, (int) $filters[$flag]);
            }
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
        if (! empty($filters['total_min'])) {
            $query->where('total', '>=', $filters['total_min']);
        }
        if (! empty($filters['total_max'])) {
            $query->where('total', '<=', $filters['total_max']);
        }

        $paginator = $query->orderBy('created_at', 'desc')->paginate($perPage);
        $totalSum = (clone $query)->sum('total');

        return [
            'transactions' => $paginator,
            'totalSum' => $totalSum,
        ];
    }
}
