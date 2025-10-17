<?php

declare(strict_types=1);

namespace App\Domain\Actions\Report;

use App\Domain\DTOs\Report\ReportStatisticRequestDTO;
use App\Domain\Services\Report\ReportServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetSoldDevicesAction
{
    public function __construct(
        private readonly ReportServiceInterface $reportService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = new ReportStatisticRequestDTO(
                date: $data['date'] ?? null,
                page: $data['page'] ?? 1,
                limit: $data['limit'] ?? 20
            );

            $result = $this->reportService->getSoldDevices($dto);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get sold devices: '.$e->getMessage(),
            ], 500);
        }
    }
}
