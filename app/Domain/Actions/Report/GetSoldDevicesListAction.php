<?php

declare(strict_types=1);

namespace App\Domain\Actions\Report;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class GetSoldDevicesListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, mixed>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = DB::table('invoice_line')
            ->join('invoice', 'invoice_line.id_invoice', '=', 'invoice.id')
            ->join('user', 'invoice.id_user', '=', 'user.id')
            ->where('invoice_line.type', 'device')
            ->select('invoice_line.*', 'user.first_name', 'user.last_name', 'invoice.created_at');

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('user.first_name', 'like', "%{$search}%")
                    ->orWhere('user.last_name', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('invoice.created_at', 'desc')->paginate($perPage);
    }
}
