<?php

declare(strict_types=1);

namespace App\Actions\Report;

use App\DTOs\Report\ReportCsvRequestDTO;
use App\Services\Report\ReportServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetReferralReportAction
{
    public function __construct(
        private readonly ReportServiceInterface $reportService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = new ReportCsvRequestDTO(
                name: $data['name'] ?? null,
                csv: $data['csv'] ?? false,
                page: $data['page'] ?? 1,
                limit: $data['limit'] ?? 20
            );

            $result = $this->reportService->getReferralReport($dto);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get referral report: '.$e->getMessage(),
            ], 500);
        }
    }
}
