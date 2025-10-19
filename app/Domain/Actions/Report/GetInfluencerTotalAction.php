<?php

declare(strict_types=1);

namespace App\Domain\Actions\Report;

use App\Domain\DTOs\Report\ReportCsvRequestDTO;
use App\Domain\Services\Report\ReportServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetInfluencerTotalAction
{
    public function __construct(
        private readonly ReportServiceInterface $reportService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        try {
            $dto = new ReportCsvRequestDTO(
                name: $data['name'] ?? null,
                csv: $data['csv'] ?? false,
                page: $data['page'] ?? 1,
                limit: $data['limit'] ?? 20
            );

            $result = $this->reportService->getInfluencerTotal($dto);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get influencer total: '.$e->getMessage(),
            ], 500);
        }
    }
}
