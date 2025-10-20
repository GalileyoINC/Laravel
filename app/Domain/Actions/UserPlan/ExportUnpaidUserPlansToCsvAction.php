<?php

declare(strict_types=1);

namespace App\Domain\Actions\UserPlan;

use App\Models\User\UserPlan;

final class ExportUnpaidUserPlansToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<int, array<int, mixed>>
     */
    /**
     * @return array<string, mixed>
     */
    public function execute(array $filters): array
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

        $items = $query->orderBy('exp_date', 'asc')->get();

        $rows = [];
        $rows[] = ['ID', 'First Name', 'Last Name', 'Email', 'Service', 'Pay Interval', 'Exp Date'];
        /** @var UserPlan $userPlan */
        foreach ($items as $userPlan) {
            $rows[] = [
                $userPlan->id,
                $userPlan->user ? $userPlan->user->first_name : '',
                $userPlan->user ? $userPlan->user->last_name : '',
                $userPlan->user ? $userPlan->user->email : '',
                $userPlan->service ? $userPlan->service->name : '',
                $userPlan->pay_interval,
                $userPlan->exp_date?->format('Y-m-d') ?? '',
            ];
        }

        return $rows;
    }
}
