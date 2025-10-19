<?php

declare(strict_types=1);

namespace App\Domain\Actions\UserPlan;

use App\Models\User\UserPlan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetUnpaidUserPlansAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $expDate = isset($filters['exp_date']) ? (int) $filters['exp_date'] : 30;

        $query = UserPlan::with(['user', 'service'])
            ->whereHas('user', function ($userQuery) {
                $userQuery->where('status', 1);
            })
            ->where('exp_date', '<=', now()->subDays($expDate));

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['id_service'])) {
            $query->where('id_service', (int) $filters['id_service']);
        }
        if (! empty($filters['pay_interval'])) {
            $query->where('pay_interval', $filters['pay_interval']);
        }

        return $query->orderBy('exp_date', 'asc')->paginate($perPage);
    }
}
