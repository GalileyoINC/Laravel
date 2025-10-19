<?php

declare(strict_types=1);

namespace App\Domain\Actions\Report;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class GetSmsReportListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, mixed>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = DB::table('sms_pool')
            ->join('user', 'sms_pool.id_user', '=', 'user.id')
            ->select('sms_pool.*', 'user.first_name', 'user.last_name');

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('user.first_name', 'like', "%{$search}%")
                    ->orWhere('user.last_name', 'like', "%{$search}%")
                    ->orWhere('sms_pool.body', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('sms_pool.created_at', 'desc')->paginate($perPage);
    }
}
