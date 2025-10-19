<?php

declare(strict_types=1);

namespace App\Domain\Actions\FollowerList;

use App\Models\Subscription\FollowerList;

final class ExportFollowerListsToCsvAction
{
    public function execute(array $filters): array
    {
        $query = FollowerList::with(['user']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }
        if (! empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }
        if (! empty($filters['userName'])) {
            $name = (string) $filters['userName'];
            $query->whereHas('user', function ($userQuery) use ($name) {
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

        $lists = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'Name', 'User', 'Is Active', 'Created At', 'Updated At'];
        foreach ($lists as $followerList) {
            $rows[] = [
                $followerList->id,
                $followerList->name,
                $followerList->user ? $followerList->user->first_name.' '.$followerList->user->last_name : '',
                $followerList->is_active ? 'Yes' : 'No',
                $followerList->created_at->format('Y-m-d H:i:s'),
                $followerList->updated_at->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
