<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Models\Device\Device;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetProductDeviceListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Device::query();

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
        if (! empty($filters['price_min'])) {
            $query->where('price', '>=', $filters['price_min']);
        }
        if (! empty($filters['price_max'])) {
            $query->where('price', '<=', $filters['price_max']);
        }

        return $query->orderBy('id')->paginate($perPage);
    }
}
