<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Domain\DTOs\Device\DeviceUpdateRequestDTO;
use App\Domain\Services\Device\DeviceServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateDeviceAction
{
    public function __construct(
        private readonly DeviceServiceInterface $deviceService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = DeviceUpdateRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid device update request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $device = $this->deviceService->updateDevice($dto, $user);

            return response()->json($device->toArray());

        } catch (Exception $e) {
            Log::error('UpdateDeviceAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
