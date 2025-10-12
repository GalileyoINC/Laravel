<?php

declare(strict_types=1);

namespace App\Modules\Content\Application\DTOs\Bundle;

readonly class BundleDeviceDataRequestDTO
{
    public function __construct(
        public int $idDevice
    ) {}
}
