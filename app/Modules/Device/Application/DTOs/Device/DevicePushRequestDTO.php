<?php

declare(strict_types=1);

namespace App\DTOs\Device;

readonly class DevicePushRequestDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $body,
        public ?array $data,
        public string $sound,
        public ?int $badge
    ) {}
}
