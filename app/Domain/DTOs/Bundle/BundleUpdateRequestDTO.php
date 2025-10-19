<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Bundle;

readonly class BundleUpdateRequestDTO
{
    /**
     * @param  array<int, mixed>  $services
     */
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $description,
        public ?float $price,
        public ?array $services,
        public ?bool $is_active
    ) {}
}
