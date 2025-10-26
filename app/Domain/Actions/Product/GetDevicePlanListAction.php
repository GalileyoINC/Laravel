<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Models\Device\DevicePlan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetDevicePlanListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, DevicePlan>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = DevicePlan::with(['service']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->whereHas('service', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('id')->paginate($perPage);
    }
}
