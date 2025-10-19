<?php

declare(strict_types=1);

namespace App\Domain\Actions\Staff;

use App\Models\System\Staff;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetStaffListAction
{
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Staff::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if (isset($filters['status'])) {
            $query->where('status', (int) $filters['status']);
        } else {
            $query->where('status', 1);
        }
        if (! empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }
        if (! empty($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
