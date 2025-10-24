<?php

declare(strict_types=1);

namespace App\Domain\Services\Device;

use App\Domain\DTOs\Device\DevicePushRequestDTO;
use App\Models\Device\Device;
use Illuminate\Support\Facades\Log;

class DeviceService implements DeviceServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getList(int $page, int $limit, ?string $search, ?int $userId, ?string $os, ?bool $pushTokenFill, ?string $pushToken, ?bool $pushTurnOn, ?string $updatedAtFrom, ?string $updatedAtTo): array
    {
        $query = Device::query()->with(['user']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('uuid', 'like', '%'.$search.'%')
                    ->orWhere('os', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', '%'.$search.'%')
                            ->orWhere('last_name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($userId) {
            $query->where('id_user', $userId);
        }

        if ($os) {
            $query->where('os', $os);
        }

        if ($pushTokenFill !== null) {
            $query->where('push_token', '!=', '');
        }

        if ($pushToken) {
            $query->where('push_token', 'like', '%'.$pushToken.'%');
        }

        if ($pushTurnOn !== null) {
            $query->where('push_turn_on', $pushTurnOn);
        }

        if ($updatedAtFrom) {
            $query->where('updated_at', '>=', $updatedAtFrom);
        }

        if ($updatedAtTo) {
            $query->where('updated_at', '<=', $updatedAtTo);
        }

        $devices = $query->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

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

    /**
     * @return array<string, mixed>
     */
    public function sendPushNotification(DevicePushRequestDTO $dto): array
    {
        $device = Device::findOrFail($dto->deviceId);

        // Mock push notification sending - replace with actual push service
        $notification = [
            'device_id' => $device->id,
            'device_uuid' => $device->uuid,
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
