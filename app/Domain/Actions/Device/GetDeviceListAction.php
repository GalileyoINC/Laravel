<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Domain\DTOs\Device\DeviceListRequestDTO;
use App\Domain\Services\Device\DeviceServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetDeviceListAction
{
    public function __construct(
        private readonly DeviceServiceInterface $deviceService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = new DeviceListRequestDTO(
                page: $data['page'] ?? 1,
                limit: $data['limit'] ?? 20,
                search: $data['search'] ?? null,
                user_id: $data['user_id'] ?? null,
                os: $data['os'] ?? null
            );

            $devices = $this->deviceService->getList($dto);

            return response()->json([
                'status' => 'success',
                'data' => $devices,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get device list: '.$e->getMessage(),
            ], 500);
        }
    }
}
