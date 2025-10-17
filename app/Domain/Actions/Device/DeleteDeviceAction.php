<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Domain\Services\Device\DeviceServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DeleteDeviceAction
{
    public function __construct(
        private readonly DeviceServiceInterface $deviceService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $this->deviceService->delete($data['id']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Device deleted successfully',
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete device: '.$e->getMessage(),
            ], 500);
        }
    }
}
