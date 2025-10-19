<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Models\Finance\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetAlertServiceListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Service::where('type', Service::TYPE_ALERT);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', (int) $filters['is_active']);
        }

        return $query->orderBy('id')->paginate($perPage);
    }
}
