<?php

declare(strict_types=1);

namespace App\Domain\Services\Maintenance;

use App\Domain\DTOs\Maintenance\SummarizeRequestDTO;
use Exception;

interface MaintenanceServiceInterface
{
    /**
     * Summarize text using OpenAI
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function summarize(SummarizeRequestDTO $dto): array;
}
