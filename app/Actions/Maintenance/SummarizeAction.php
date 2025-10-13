<?php

declare(strict_types=1);

namespace App\Actions\Maintenance;

use App\DTOs\Maintenance\SummarizeRequestDTO;
use App\Services\Maintenance\MaintenanceServiceInterface;

class SummarizeAction
{
    public function __construct(
        private readonly MaintenanceServiceInterface $maintenanceService
    ) {}

    /**
     * Execute the summarize action
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function execute(array $data): array
    {
        $dto = SummarizeRequestDTO::fromArray($data);
        
        return $this->maintenanceService->summarize($dto);
    }
}
