<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Product;

readonly class DevicePlanCreateDTO
{
    public function __construct(
        public string $name,
        public ?string $description,
        public float $price,
        public bool $isActive,
    ) {}
}
