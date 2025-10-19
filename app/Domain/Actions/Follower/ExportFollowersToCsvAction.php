<?php

declare(strict_types=1);

namespace App\Domain\Actions\Follower;

use App\Models\Subscription\Follower;

final class ExportFollowersToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<int, array<int, mixed>>
     */
    public function execute(array $filters): array
    {
        $query = Follower::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['followerListName'])) {
            $name = (string) $filters['followerListName'];
            $query->where('id', 'like', "%{$name}%");
        }
        if (! empty($filters['userLeaderName'])) {
            $name = (string) $filters['userLeaderName'];
            $query->where('id', 'like', "%{$name}%");
        }
        if (! empty($filters['userFollowerName'])) {
            $name = (string) $filters['userFollowerName'];
            $query->where('id', 'like', "%{$name}%");
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', (int) $filters['is_active']);
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }
        if (! empty($filters['updated_at_from'])) {
            $query->whereDate('updated_at', '>=', $filters['updated_at_from']);
        }
        if (! empty($filters['updated_at_to'])) {
            $query->whereDate('updated_at', '<=', $filters['updated_at_to']);
        }

        $followers = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Is Active', 'Created At', 'Updated At'];
        foreach ($followers as $follower) {
            $rows[] = [
                $follower->id,
                $follower->is_active ? 'Yes' : 'No',
                $follower->created_at->format('Y-m-d H:i:s'),
                $follower->updated_at?->format('Y-m-d H:i:s') ?? '',
            ];
        }

        return $rows;
    }
}
