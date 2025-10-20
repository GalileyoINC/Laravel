<?php

declare(strict_types=1);

namespace App\Domain\Actions\Apple;

use App\Models\Order\AppleAppTransaction;

final class ExportAppleAppTransactionsToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<list<mixed>>
     */
    public function execute(array $filters): array
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

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Transaction ID', 'Status', 'Error', 'User ID', 'Is Process', 'Created At'];
        /** @var AppleAppTransaction $transaction */
        foreach ($transactions as $transaction) {
            $rows[] = [
                $transaction->id,
                $transaction->transaction_id,
                $transaction->status,
                $transaction->error,
                $transaction->id_user,
                $transaction->is_process ? 'Yes' : 'No',
                $transaction->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
