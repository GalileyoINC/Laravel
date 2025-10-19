<?php

declare(strict_types=1);

namespace App\Domain\Actions\Finance;

use App\Models\Finance\MoneyTransaction;

final class ExportMoneyTransactionsToCsvAction
{
    public function execute(array $filters): array
    {
        $query = MoneyTransaction::with(['user', 'creditCard']);

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

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'User', 'Invoice', 'Credit Card', 'Transaction ID', 'Success', 'Void', 'Test', 'Total', 'Created At', 'Updated At'];
        foreach ($transactions as $transaction) {
            $card = null;
            if ($transaction->creditCard) {
                $card = ($transaction->creditCard->type ? $transaction->creditCard->type.' ' : '').
                        $transaction->creditCard->num.' ('.
                        $transaction->creditCard->expiration_year.'/'.
                        $transaction->creditCard->expiration_month.')';
            }
            $rows[] = [
                $transaction->id,
                $transaction->user ? $transaction->user->first_name.' '.$transaction->user->last_name : '',
                $transaction->id_invoice,
                $card,
                $transaction->transaction_id,
                $transaction->is_success ? 'Yes' : 'No',
                $transaction->is_void ? 'Yes' : 'No',
                $transaction->is_test ? 'Yes' : 'No',
                number_format((float) $transaction->total, 2, '.', ''),
                $transaction->created_at->toDateTimeString(),
                $transaction->updated_at ? $transaction->updated_at->toDateTimeString() : '',
            ];
        }

        return $rows;
    }
}
