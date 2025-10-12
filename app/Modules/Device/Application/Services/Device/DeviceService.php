<?php

declare(strict_types=1);

namespace App\Services\Device;

use App\DTOs\Device\DeviceListRequestDTO;
use App\DTOs\Device\DevicePushRequestDTO;
use App\Models\Device\Device\Device;
use Log;

class DeviceService implements DeviceServiceInterface
{
    public function getList(DeviceListRequestDTO $dto): array
    {
        $query = Device::query()->with(['user']);

        if ($dto->search) {
            $query->where(function ($q) use ($dto) {
                $q->where('device_uuid', 'like', '%'.$dto->search.'%')
                    ->orWhere('device_name', 'like', '%'.$dto->search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($dto) {
                        $userQuery->where('first_name', 'like', '%'.$dto->search.'%')
                            ->orWhere('last_name', 'like', '%'.$dto->search.'%')
                            ->orWhere('email', 'like', '%'.$dto->search.'%');
                    });
            });
        }

        if ($dto->user_id) {
            $query->where('id_user', $dto->user_id);
        }

        if ($dto->os) {
            $query->where('os', $dto->os);
        }

        $devices = $query->orderBy('created_at', 'desc')
            ->paginate($dto->limit, ['*'], 'page', $dto->page);

        return [
            'data' => $devices->items(),
            'pagination' => [
                'current_page' => $devices->currentPage(),
                'last_page' => $devices->lastPage(),
                'per_page' => $devices->perPage(),
                'total' => $devices->total(),
            ],
        ];
    }

    public function getById(int $id): Device
    {
        return Device::with(['user'])->findOrFail($id);
    }

    public function delete(int $id): void
    {
        $device = Device::findOrFail($id);
        $device->delete();
    }

    public function sendPushNotification(DevicePushRequestDTO $dto): array
    {
        $device = Device::findOrFail($dto->id);

        // Mock push notification sending - replace with actual push service
        $notification = [
            'device_id' => $device->id,
            'device_uuid' => $device->device_uuid,
            'title' => $dto->title,
            'body' => $dto->body,
            'data' => $dto->data,
            'sound' => $dto->sound,
            'badge' => $dto->badge,
            'sent_at' => now(),
            'status' => 'sent',
        ];

        // Log the notification (in real implementation, send to push service)
        Log::info('Push notification sent', $notification);

        return $notification;
    }
}
