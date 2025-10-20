<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Models\Device\Device;
use App\Models\User\User;

final class ExportDevicesToCsvAction
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<int, array<int, mixed>>
     */
    /**
     * @return array<string, mixed>
     */
    public function execute(array $filters): array
    {
        $query = Device::with(['user']);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('uuid', 'like', "%{$search}%")
                    ->orWhere('os', 'like', "%{$search}%")
                    ->orWhere('push_token', 'like', "%{$search}%")
                    ->orWhere('access_token', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'like', "%{$search}%");
                    });
            });
        }
        if (isset($filters['push_token_fill'])) {
            if ((int) $filters['push_token_fill'] === 0) {
                $query->where(function ($q) {
                    $q->whereNull('push_token')->orWhere('push_token', '');
                });
            } else {
                $query->whereNotNull('push_token')->where('push_token', '!=', '');
            }
        }
        if (! empty($filters['push_token'])) {
            $query->where('push_token', 'like', "%{$filters['push_token']}%");
        }
        if (isset($filters['push_turn_on'])) {
            $query->where('push_turn_on', (int) $filters['push_turn_on']);
        }
        if (! empty($filters['os'])) {
            $query->where('os', $filters['os']);
        }
        if (! empty($filters['updated_at_from'])) {
            $query->whereDate('updated_at', '>=', $filters['updated_at_from']);
        }
        if (! empty($filters['updated_at_to'])) {
            $query->whereDate('updated_at', '<=', $filters['updated_at_to']);
        }

        $devices = $query->orderBy('updated_at', 'desc')->get();

        $rows = [];
        $rows[] = ['ID', 'User Email', 'User ID', 'Push Turn On', 'UUID', 'OS', 'Push Token', 'Access Token', 'Updated At'];
        foreach ($devices as $device) {
            /** @var Device $device */
            $userEmail = '';
            $userId = '';
            if ($device->user) {
                /** @var User $user */
                $user = $device->user;
                $userEmail = $user->email ?? '';
                $userId = (string) $user->id;
            }

            $rows[] = [
                $device->id,
                $userEmail,
                $userId,
                $device->push_turn_on ? 'Yes' : 'No',
                $device->uuid,
                $device->os,
                $device->push_token,
                $device->access_token,
                $device->updated_at?->format('Y-m-d H:i:s') ?? '',
            ];
        }

        return $rows;
    }
}
