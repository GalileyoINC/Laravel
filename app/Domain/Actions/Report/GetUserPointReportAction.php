<?php

declare(strict_types=1);

namespace App\Domain\Actions\Report;

use Illuminate\Support\Facades\DB;

final class GetUserPointReportAction
{
    /**
     * @return array<int, array{name: string, email: string, points: int, updated_at: string}>
     */
    public function execute(?string $name = null): array
    {
        $query = DB::table('user')
            ->leftJoin('user_point_history', 'user.id', '=', 'user_point_history.id_user')
            ->select(
                'user.id',
                'user.first_name',
                'user.last_name',
                'user.email',
                'user.updated_at',
                DB::raw('COALESCE(SUM(user_point_history.quantity), 0) + COALESCE(user.bonus_point, 0) as points')
            )
            ->groupBy('user.id', 'user.first_name', 'user.last_name', 'user.email', 'user.updated_at', 'user.bonus_point');

        if ($name) {
            $query->where(function ($q) use ($name) {
                $q->where('user.first_name', 'like', "%{$name}%")
                    ->orWhere('user.last_name', 'like', "%{$name}%");
            });
        }

        $results = $query->orderBy('points', 'desc')->get();

        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'name' => trim("{$result->first_name} {$result->last_name}"),
                'email' => $result->email,
                'points' => (int) $result->points,
                'updated_at' => $result->updated_at,
            ];
        }

        return $data;
    }
}
