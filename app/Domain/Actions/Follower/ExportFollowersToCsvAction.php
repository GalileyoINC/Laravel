<?php

declare(strict_types=1);

namespace App\Domain\Actions\Follower;

use App\Models\Subscription\Follower;

final class ExportFollowersToCsvAction
{
    public function execute(array $filters): array
    {
        $query = Follower::with(['followerList', 'userLeader', 'userFollower']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('followerList', function ($followerListQuery) use ($search) {
                    $followerListQuery->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('userLeader', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('userFollower', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }
        if (! empty($filters['followerListName'])) {
            $name = (string) $filters['followerListName'];
            $query->whereHas('followerList', function ($followerListQuery) use ($name) {
                $followerListQuery->where('name', 'like', "%{$name}%");
            });
        }
        if (! empty($filters['userLeaderName'])) {
            $name = (string) $filters['userLeaderName'];
            $query->whereHas('userLeader', function ($userQuery) use ($name) {
                $userQuery->where('first_name', 'like', "%{$name}%")
                    ->orWhere('last_name', 'like', "%{$name}%");
            });
        }
        if (! empty($filters['userFollowerName'])) {
            $name = (string) $filters['userFollowerName'];
            $query->whereHas('userFollower', function ($userQuery) use ($name) {
                $userQuery->where('first_name', 'like', "%{$name}%")
                    ->orWhere('last_name', 'like', "%{$name}%");
            });
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
        $rows[] = ['ID', 'List', 'Leader', 'Follower', 'Is Active', 'Created At', 'Updated At'];
        foreach ($followers as $follower) {
            $rows[] = [
                $follower->id,
                $follower->followerList ? $follower->followerList->name : '',
                $follower->userLeader ? $follower->userLeader->first_name.' '.$follower->userLeader->last_name : '',
                $follower->userFollower ? $follower->userFollower->first_name.' '.$follower->userFollower->last_name : '',
                $follower->is_active ? 'Yes' : 'No',
                $follower->created_at->format('Y-m-d H:i:s'),
                $follower->updated_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
