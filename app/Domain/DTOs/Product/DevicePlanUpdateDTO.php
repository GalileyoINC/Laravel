<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Product;

readonly class DevicePlanUpdateDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public float $price,
        public bool $isActive,
    ) {}
}
