<?php

declare(strict_types=1);

namespace App\Domain\Actions\Apple;

use App\Models\Notification\AppleNotification;

final class ExportAppleNotificationsToCsvAction
{
    public function execute(array $filters): array
    {
        $query = AppleNotification::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('notification_type', 'like', "%{$search}%")
                    ->orWhere('subtype', 'like', "%{$search}%")
                    ->orWhere('notification_uuid', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['notification_type'])) {
            $query->where('notification_type', $filters['notification_type']);
        }
        if (! empty($filters['subtype'])) {
            $query->where('subtype', $filters['subtype']);
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }

        $notifications = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Notification Type', 'Subtype', 'Notification UUID', 'Created At'];
        foreach ($notifications as $notification) {
            $rows[] = [
                $notification->id,
                $notification->notification_type,
                $notification->subtype,
                $notification->notification_uuid,
                $notification->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
