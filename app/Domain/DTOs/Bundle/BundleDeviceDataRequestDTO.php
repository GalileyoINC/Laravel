<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Bundle;

readonly class BundleDeviceDataRequestDTO
{
    public function __construct(
        public int $idDevice
    ) {}
}
