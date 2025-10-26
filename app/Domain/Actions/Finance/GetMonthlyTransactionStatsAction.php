<?php

declare(strict_types=1);

namespace App\Domain\Actions\Finance;

use App\Models\Finance\MoneyTransaction;
use Illuminate\Support\Carbon;

class GetMonthlyTransactionStatsAction
{
    /**
     * Get monthly transaction statistics for a year
     *
     * @return array<string, array{month: string, total: float, count: int, success_count: int, fail_count: int}>
     */
    public function execute(string $year): array
    {
        $startDate = Carbon::createFromDate((int) $year, 1, 1)->startOfYear();
        $endDate = Carbon::createFromDate((int) $year, 12, 31)->endOfYear();

        $transactions = MoneyTransaction::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                MONTH(created_at) as month,
                SUM(total) as total,
                COUNT(*) as count,
                SUM(CASE WHEN is_success = 1 THEN 1 ELSE 0 END) as success_count,
                SUM(CASE WHEN is_success = 0 OR is_success IS NULL THEN 1 ELSE 0 END) as fail_count
            ')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $stats = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::createFromDate((int) $year, $month, 1)->format('M Y');
            $transaction = $transactions->firstWhere('month', $month);

            $stats[] = [
                'month' => $monthName,
                'total' => $transaction ? (float) $transaction->total : 0.0,
                'count' => $transaction ? (int) $transaction->count : 0,
                'success_count' => $transaction ? (int) $transaction->success_count : 0,
                'fail_count' => $transaction ? (int) $transaction->fail_count : 0,
            ];
        }

        return $stats;
    }
}

