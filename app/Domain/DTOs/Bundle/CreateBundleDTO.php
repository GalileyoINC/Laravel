<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Bundle;

readonly class CreateBundleDTO
{
    public function __construct(
        public string $title,
        public ?int $type = 1,
        public ?int $payInterval = 1,
        public ?bool $isActive = true,
        public ?float $total = 0.0
    ) {}
}
