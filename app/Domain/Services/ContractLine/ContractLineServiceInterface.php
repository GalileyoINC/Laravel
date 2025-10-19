<?php

declare(strict_types=1);

namespace App\Domain\Services\ContractLine;

use App\Domain\DTOs\ContractLine\ContractLineListRequestDTO;

interface ContractLineServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getUnpaidContracts(ContractLineListRequestDTO $dto): array;
}
