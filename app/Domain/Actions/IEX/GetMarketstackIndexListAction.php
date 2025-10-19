<?php

declare(strict_types=1);

namespace App\Domain\Actions\IEX;

use App\Models\System\MarketstackIndx;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetMarketstackIndexListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
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

        return $query->orderBy('name')->paginate($perPage);
    }
}
