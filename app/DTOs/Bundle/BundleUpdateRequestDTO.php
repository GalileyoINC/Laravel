<?php

declare(strict_types=1);

namespace App\DTOs\Bundle;

readonly class BundleUpdateRequestDTO
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $description,
        public ?float $price,
        public ?array $services,
        public ?bool $is_active
    ) {}
}
