<?php

declare(strict_types=1);

namespace App\Domain\Actions\Maintenance;

use App\Domain\DTOs\Maintenance\SummarizeRequestDTO;
use App\Domain\Services\Maintenance\MaintenanceServiceInterface;
use Exception;

class SummarizeAction
{
    public function __construct(
        private readonly MaintenanceServiceInterface $maintenanceService
    ) {}

    /**
     * Execute the summarize action
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    /**
     * @return array<string, mixed>
     */
    public function execute(array $data): array
    {
        $dto = SummarizeRequestDTO::fromArray($data);

        return $this->maintenanceService->summarize($dto);
    }
}
