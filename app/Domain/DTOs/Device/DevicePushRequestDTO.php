<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Device;

readonly class DevicePushRequestDTO
{
    public function __construct(
        public int $deviceId,
        public string $title,
        public string $body,
        public ?array $data = null,
        public string $sound = 'default',
        public ?int $badge = null
    ) {}

    public function toArray(): array
    {
        return [
            'device_id' => $this->deviceId,
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
            'sound' => $this->sound,
            'badge' => $this->badge,
        ];
    }
}
