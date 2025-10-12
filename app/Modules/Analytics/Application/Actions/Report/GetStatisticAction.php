<?php

declare(strict_types=1);

namespace App\Modules\System\Application\Actions\Report;

use App\DTOs\Report\ReportStatisticRequestDTO;
use App\Services\Report\ReportServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetStatisticAction
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

            $result = $this->reportService->getStatistic($dto);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get statistic: '.$e->getMessage(),
            ], 500);
        }
    }
}
