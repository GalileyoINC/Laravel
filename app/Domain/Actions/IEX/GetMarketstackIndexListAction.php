<?php

declare(strict_types=1);

namespace App\Domain\Actions\IEX;

use App\Models\System\Setting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetMarketstackIndexListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Setting>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Setting::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('prod', 'like', "%{$search}%")
                    ->orWhere('dev', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }

        return $query->orderBy('name')->paginate($perPage);
    }
}
