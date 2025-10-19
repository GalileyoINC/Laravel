<?php

declare(strict_types=1);

namespace App\Domain\Actions\Bundle;

use App\Domain\DTOs\Bundle\BundleDeviceDataRequestDTO;
use App\Domain\Services\Bundle\BundleServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetBundleDeviceDataAction
{
    public function __construct(
        private readonly BundleServiceInterface $bundleService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
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
