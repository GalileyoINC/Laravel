<?php

declare(strict_types=1);

namespace App\Domain\Services\ContractLine;

interface ContractLineServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getUnpaidContracts(int $page, int $limit, ?string $search, ?int $userId, ?int $serviceId, string $status): array;
}
