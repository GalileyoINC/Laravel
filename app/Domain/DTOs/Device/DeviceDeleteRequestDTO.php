<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Device;

readonly class DeviceDeleteRequestDTO
{
    public function __construct(
        public int $id
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
