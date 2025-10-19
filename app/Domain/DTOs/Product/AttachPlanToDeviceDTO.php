<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Product;

readonly class AttachPlanToDeviceDTO
{
    public function __construct(
        public int $deviceId,
        public int $planId,
        public ?float $price = null,
        public ?bool $isDefault = null,
    ) {}
}
