<?php

declare(strict_types=1);

namespace App\Domain\Actions\IEX;

use App\Models\System\MarketstackIndx;

final class ExportMarketstackIndexesToCsvAction
{
    public function execute(array $filters): array
    {
        $query = MarketstackIndx::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('symbol', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%")
                    ->orWhere('currency', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['country'])) {
            $query->where('country', $filters['country']);
        }
        if (! empty($filters['currency'])) {
            $query->where('currency', $filters['currency']);
        }
        if (isset($filters['has_intraday'])) {
            $query->where('has_intraday', (int) $filters['has_intraday']);
        }
        if (isset($filters['has_eod'])) {
            $query->where('has_eod', (int) $filters['has_eod']);
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', (int) $filters['is_active']);
        }

        $items = $query->orderBy('name')->get();

        $rows = [];
        $rows[] = ['ID', 'Name', 'Symbol', 'Country', 'Currency', 'Has Intraday', 'Has EOD', 'Is Active'];
        foreach ($items as $index) {
            $rows[] = [
                $index->id,
                $index->name,
                $index->symbol,
                $index->country,
                $index->currency,
                $index->has_intraday ? 'Yes' : 'No',
                $index->has_eod ? 'Yes' : 'No',
                $index->is_active ? 'Yes' : 'No',
            ];
        }

        return $rows;
    }
}
