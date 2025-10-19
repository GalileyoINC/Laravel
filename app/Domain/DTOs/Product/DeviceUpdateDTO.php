<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Product;

readonly class DeviceUpdateDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public float $price,
        public ?float $specialPrice,
        public bool $isSpecialPrice,
        public bool $isActive,
    ) {}
}
