<?php

declare(strict_types=1);

namespace App\Actions\Device;

use App\DTOs\Device\DeviceVerifyRequestDTO;
use App\Services\Device\DeviceServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VerifyDeviceAction
{
    public function __construct(
        private readonly DeviceServiceInterface $deviceService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = DeviceVerifyRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid device verify request'],
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

            $device = $this->deviceService->verifyDevice($dto, $user);

            return response()->json($device->toArray());

        } catch (Exception $e) {
            Log::error('VerifyDeviceAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
