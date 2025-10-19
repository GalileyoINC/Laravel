<?php

declare(strict_types=1);

namespace App\Domain\Actions\ContractLine;

use App\Models\Finance\ContractLine;

final class ExportUnpaidContractLinesToCsvAction
{
    public function execute(array $filters): array
    {
        $query = ContractLine::with(['user', 'service'])
            ->whereHas('userPlan', function ($q) {
                $q->where('end_at', '<', now());
            });

        $expDateDays = (int) ($filters['exp_date'] ?? 30);
        if ($expDateDays) {
            $query->where('end_at', '>=', now()->subDays($expDateDays));
        }
        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['id_service'])) {
            $query->where('id_service', (int) $filters['id_service']);
        }
        if (! empty($filters['pay_interval'])) {
            $query->where('pay_interval', (int) $filters['pay_interval']);
        }

        $contractLines = $query->orderBy('end_at', 'asc')->get();

        $rows = [];
        $rows[] = ['User ID', 'First Name', 'Last Name', 'Email', 'Service', 'Pay Interval', 'End At'];
        foreach ($contractLines as $contractLine) {
            $rows[] = [
                $contractLine->id_user,
                $contractLine->first_name,
                $contractLine->last_name,
                $contractLine->email,
                $contractLine->service_name,
                $contractLine->pay_interval ? ($contractLine->pay_interval === 1 ? 'Monthly' : ($contractLine->pay_interval === 12 ? 'Annual' : (string) $contractLine->pay_interval)) : '',
                $contractLine->end_at ? $contractLine->end_at->format('Y-m-d') : '',
            ];
        }

        return $rows;
    }
}
