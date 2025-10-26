<?php

declare(strict_types=1);

namespace App\Domain\Actions\Report;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class GetDevicesPlansListAction
{
    /**
     * @return LengthAwarePaginator<int, mixed>
     */
    public function execute(?string $date = null, array $filters = []): LengthAwarePaginator
    {
        $targetDate = $date ? \Carbon\Carbon::parse($date) : \Carbon\Carbon::now();

        $query = DB::table('invoice_line')
            ->join('invoice', 'invoice_line.id_invoice', '=', 'invoice.id')
            ->join('user', 'invoice.id_user', '=', 'user.id')
            ->leftJoin('bundle', 'invoice_line.id_bundle', '=', 'bundle.id')
            ->leftJoin('service', 'invoice_line.id_service', '=', 'service.id')
            ->where('invoice_line.type', 'device')
            ->whereDate('invoice.created_at', $targetDate->format('Y-m-d'))
            ->select(
                'invoice_line.*',
                'user.first_name',
                'user.last_name',
                'invoice.created_at',
                DB::raw('COALESCE(bundle.title, service.name, "Unknown") as product_name'),
                'invoice_line.total as price'
            );

        return $query->orderBy('invoice.created_at', 'desc')->paginate(20);
    }
}
