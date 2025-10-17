<?php

declare(strict_types=1);

namespace App\Domain\Services\Report;

use App\Domain\DTOs\Report\ReportCsvRequestDTO;
use App\Domain\DTOs\Report\ReportStatisticRequestDTO;

interface ReportServiceInterface
{
    public function getLoginStatistic(ReportStatisticRequestDTO $dto): array;

    public function getSoldDevices(ReportStatisticRequestDTO $dto): array;

    public function getInfluencerTotal(ReportCsvRequestDTO $dto): array;

    public function getReferralReport(ReportCsvRequestDTO $dto): array;

    public function getStatistic(ReportStatisticRequestDTO $dto): array;

    public function getSmsReport(ReportStatisticRequestDTO $dto): array;
}
