<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmergencyTipsRequest;

use App\Models\User\EmergencyTipsRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetEmergencyTipsRequestListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, EmergencyTipsRequest>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = EmergencyTipsRequest::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['first_name'])) {
            $query->where('first_name', 'like', "%{$filters['first_name']}%");
        }
        if (! empty($filters['email'])) {
            $query->where('email', 'like', "%{$filters['email']}%");
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
