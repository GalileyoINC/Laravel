<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Bundle;

readonly class UpdateBundleDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public ?int $type = null,
        public ?int $payInterval = null,
        public ?bool $isActive = null,
        public ?float $total = null
    ) {}
}
