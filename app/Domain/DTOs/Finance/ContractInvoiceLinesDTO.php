<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Finance;

class ContractInvoiceLinesDTO
{
    public function __construct(
        public int $contractLineId,
    ) {}
}
