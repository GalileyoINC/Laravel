<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Product;

readonly class SubscriptionUpdateDTO
{
    public function __construct(
        public int $serviceId,
        public string $name,
        public ?string $description,
        public float $price,
        public ?float $specialPrice,
        public bool $isSpecialPrice,
        public bool $isActive,
        /** @var array<string,mixed> */
        public array $settings = [],
    ) {}
}
