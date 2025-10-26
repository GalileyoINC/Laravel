<?php

declare(strict_types=1);

namespace App\Domain\Actions\Report;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final class GetLoginStatisticAction
{
    /**
     * @return array<int, array{name: string, email: string, last_login: string, count_month: int}>
     */
    public function execute(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();

        $results = DB::table('login_statistic')
            ->join('user', 'login_statistic.id_user', '=', 'user.id')
            ->select(
                'user.first_name',
                'user.last_name',
                'user.email',
                DB::raw('MAX(login_statistic.created_at) as last_login'),
                DB::raw('COUNT(*) as count_month')
            )
            ->where('login_statistic.created_at', '>=', $startOfMonth)
            ->groupBy('user.id', 'user.first_name', 'user.last_name', 'user.email')
            ->orderBy('last_login', 'desc')
            ->get();

        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'name' => trim("{$result->first_name} {$result->last_name}"),
                'email' => $result->email,
                'last_login' => $result->last_login,
                'count_month' => (int) $result->count_month,
            ];
        }

        return $data;
    }
}
