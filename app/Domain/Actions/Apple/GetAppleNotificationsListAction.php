<?php

declare(strict_types=1);

namespace App\Domain\Actions\Apple;

use App\Models\Notification\AppleNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetAppleNotificationsListAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, AppleNotification>
     */
    public function execute(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = AppleNotification::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('notification_type', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['notification_type'])) {
            $query->where('notification_type', $filters['notification_type']);
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
