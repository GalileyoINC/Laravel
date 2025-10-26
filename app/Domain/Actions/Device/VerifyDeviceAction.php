<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Domain\DTOs\Device\DeviceVerifyRequestDTO;
use App\Domain\Services\Device\DeviceServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VerifyDeviceAction
{
    public function __construct(
        private readonly DeviceServiceInterface $deviceService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
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

        $device = $this->deviceService->getById(1); // Placeholder - implement actual verification

        return response()->json($device->toArray());
    }
}
