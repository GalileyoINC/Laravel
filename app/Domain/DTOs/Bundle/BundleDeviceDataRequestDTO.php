<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Bundle;

class BundleDeviceDataRequestDTO
{
    public function __construct(
        public readonly int $idDevice
    ) {}

    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id_device' => $this->idDevice,
        ];
    }
}
