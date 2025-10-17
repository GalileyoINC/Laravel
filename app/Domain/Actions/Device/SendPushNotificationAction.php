<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Domain\DTOs\Device\DevicePushRequestDTO;
use App\Domain\Services\Device\DeviceServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SendPushNotificationAction
{
    public function __construct(
        private readonly DeviceServiceInterface $deviceService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $dto = new DevicePushRequestDTO(
                id: $data['id'],
                title: $data['title'],
                body: $data['body'],
                data: $data['data'] ?? null,
                sound: $data['sound'] ?? 'default',
                badge: $data['badge'] ?? null
            );

            $result = $this->deviceService->sendPushNotification($dto);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Push notification sent successfully',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send push notification: '.$e->getMessage(),
            ], 500);
        }
    }
}
