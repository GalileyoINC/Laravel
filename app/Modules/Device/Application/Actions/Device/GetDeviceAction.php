<?php

declare(strict_types=1);

namespace App\Actions\Device;

use App\Services\Device\DeviceServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetDeviceAction
{
    public function __construct(
        private readonly DeviceServiceInterface $deviceService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $device = $this->deviceService->getById($data['id']);

            return response()->json([
                'status' => 'success',
                'data' => $device,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get device: '.$e->getMessage(),
            ], 500);
        }
    }
}
