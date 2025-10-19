<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Finance;

class TerminateContractDTO
{
    public function __construct(
        public int $contractLineId,
        public string $terminatedAt,
    ) {}
}
