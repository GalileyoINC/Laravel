<?php

declare(strict_types=1);

namespace App\Domain\Actions\Apple;

use App\Models\Notification\AppleNotification;

final class ExportAppleNotificationsToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<int, array<int, mixed>>
     */
    public function execute(array $filters): array
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

        $notifications = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Notification Type', 'Transaction ID', 'Created At'];
        /** @var AppleNotification $notification */
        foreach ($notifications as $notification) {
            $rows[] = [
                $notification->id,
                $notification->notification_type,
                $notification->transaction_id,
                $notification->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
