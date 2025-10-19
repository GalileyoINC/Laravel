<?php

declare(strict_types=1);

namespace App\Domain\Actions\ContractLine;

use App\Models\Finance\ContractLine;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetUnpaidContractLinesAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
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

        return $query->orderBy('end_at', 'asc')->paginate($perPage);
    }
}
