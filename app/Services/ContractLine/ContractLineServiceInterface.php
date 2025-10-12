<?php

declare(strict_types=1);

namespace App\Services\ContractLine;

use App\DTOs\ContractLine\ContractLineListRequestDTO;

interface ContractLineServiceInterface
{
    public function getUnpaidContracts(ContractLineListRequestDTO $dto): array;
}
