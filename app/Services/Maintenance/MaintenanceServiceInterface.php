<?php

declare(strict_types=1);

namespace App\Services\Maintenance;

use App\DTOs\Maintenance\SummarizeRequestDTO;

interface MaintenanceServiceInterface
{
    /**
     * Summarize text using OpenAI
     *
     * @param SummarizeRequestDTO $dto
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function summarize(SummarizeRequestDTO $dto): array;
}
