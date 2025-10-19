<?php

declare(strict_types=1);

namespace App\Domain\Services\Report;

use App\Domain\DTOs\Report\ReportCsvRequestDTO;
use App\Domain\DTOs\Report\ReportStatisticRequestDTO;

interface ReportServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getLoginStatistic(ReportStatisticRequestDTO $dto): array;

    /**
     * @return array<string, mixed>
     */
    public function getSoldDevices(ReportStatisticRequestDTO $dto): array;

    /**
     * @return array<string, mixed>
     */
    public function getInfluencerTotal(ReportCsvRequestDTO $dto): array;

    /**
     * @return array<string, mixed>
     */
    public function getReferralReport(ReportCsvRequestDTO $dto): array;

    /**
     * @return array<string, mixed>
     */
    public function getStatistic(ReportStatisticRequestDTO $dto): array;

    /**
     * @return array<string, mixed>
     */
    public function getSmsReport(ReportStatisticRequestDTO $dto): array;
}
