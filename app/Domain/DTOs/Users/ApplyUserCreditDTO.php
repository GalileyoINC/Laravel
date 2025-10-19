<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Users;

class ApplyUserCreditDTO
{
    public function __construct(
        public int $userId,
        public float $amount,
        public string $reason,
    ) {}
}
