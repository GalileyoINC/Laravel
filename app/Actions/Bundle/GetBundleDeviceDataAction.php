<?php

declare(strict_types=1);

namespace App\Actions\Bundle;

use App\DTOs\Bundle\BundleDeviceDataRequestDTO;
use App\Services\Bundle\BundleServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetBundleDeviceDataAction
{
    public function __construct(
        private readonly BundleServiceInterface $bundleService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = new BundleDeviceDataRequestDTO(
                idDevice: $data['idDevice']
            );

            $result = $this->bundleService->getDeviceData($dto);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get device data: '.$e->getMessage(),
            ], 500);
        }
    }
}
