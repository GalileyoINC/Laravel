<?php

declare(strict_types=1);

namespace App\Domain\DTOs\IEX;

class MarketstackIndexUpdateDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $symbol,
        public readonly string $country,
        public readonly string $currency,
        public readonly bool $hasIntraday,
        public readonly bool $hasEod,
        public readonly bool $isActive,
    ) {}
}
