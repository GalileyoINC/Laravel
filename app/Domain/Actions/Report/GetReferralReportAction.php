<?php

declare(strict_types=1);

namespace App\Domain\Actions\Report;

use App\Domain\DTOs\Report\ReportCsvRequestDTO;
use App\Domain\Services\Report\ReportServiceInterface;
use Illuminate\Http\JsonResponse;

class GetReferralReportAction
{
    public function __construct(
        private readonly ReportServiceInterface $reportService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
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
    }
}
