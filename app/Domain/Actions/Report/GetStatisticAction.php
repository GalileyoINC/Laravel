<?php

declare(strict_types=1);

namespace App\Domain\Actions\Report;

use App\Domain\DTOs\Report\ReportStatisticRequestDTO;
use App\Domain\Services\Report\ReportServiceInterface;
use Illuminate\Http\JsonResponse;

class GetStatisticAction
{
    public function __construct(
        private readonly ReportServiceInterface $reportService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
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
    }
}
