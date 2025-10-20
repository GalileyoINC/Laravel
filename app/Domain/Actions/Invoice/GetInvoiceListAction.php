<?php

declare(strict_types=1);

namespace App\Domain\Actions\Invoice;

use App\Models\Finance\Invoice;
use Illuminate\Support\Carbon;

final class GetInvoiceListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function execute(array $filters, int $perPage = 20): array
    {
        $query = Invoice::with(['user', 'invoiceLines', 'moneyTransactions']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['paid_status'])) {
            $query->where('paid_status', $filters['paid_status']);
        }

        if (! empty($filters['createTimeRange'])) {
            $dateRange = explode(' - ', (string) $filters['createTimeRange']);
            if (count($dateRange) === 2) {
                $query->whereBetween('created_at', [
                    Carbon::parse($dateRange[0])->startOfDay(),
                    Carbon::parse($dateRange[1])->endOfDay(),
                ]);
            }
        }

        if (! empty($filters['total_min'])) {
            $query->where('total', '>=', $filters['total_min']);
        }
        if (! empty($filters['total_max'])) {
            $query->where('total', '<=', $filters['total_max']);
        }

        $query->orderBy('created_at', 'desc');

        $invoices = $query->paginate($perPage);

        $totalSum = (clone $query)->sum('total');

        return [
            'invoices' => $invoices,
            'totalSum' => $totalSum,
        ];
    }
}
