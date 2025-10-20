<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Domain\DTOs\Device\DevicePushRequestDTO;
use App\Domain\Services\Device\DeviceServiceInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SendPushNotificationAction
{
    public function __construct(
        private readonly DeviceServiceInterface $deviceService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function execute(array $data): array
    {
        DB::beginTransaction();

        try {
            $dto = new DevicePushRequestDTO(
                deviceId: $data['device_id'],
                title: $data['title'],
                body: $data['body'],
                data: $data['data'] ?? null,
                sound: $data['sound'] ?? 'default',
                badge: $data['badge'] ?? null
            );

            $result = $this->deviceService->sendPushNotification($dto);
            DB::commit();

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
