<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Product;

readonly class DeviceCreateDTO
{
    public function __construct(
        public string $name,
        public ?string $description,
        public float $price,
        public ?float $specialPrice,
        public bool $isSpecialPrice,
        public bool $isActive,
    ) {}
}
