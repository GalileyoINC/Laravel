<?php

declare(strict_types=1);

namespace App\DTOs\Bundle;

readonly class BundleDeviceDataRequestDTO
{
    public function __construct(
        public int $idDevice
    ) {}
}
