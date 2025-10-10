<?php

namespace App\DTOs\Device;

use Illuminate\Http\Request;

class DeviceUpdateRequestDTO
{
    public function __construct(
        public readonly ?string $os = null,
        public readonly ?string $pushToken = null,
        public readonly ?array $info = []
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            os: $data['os'] ?? null,
            pushToken: $data['push_token'] ?? null,
            info: $data['info'] ?? []
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            os: $request->input('os'),
            pushToken: $request->input('push_token'),
            info: $request->input('info', [])
        );
    }

    public function toArray(): array
    {
        return [
            'os' => $this->os,
            'push_token' => $this->pushToken,
            'info' => $this->info
        ];
    }

    public function validate(): bool
    {
        return true; // All fields are optional
    }
}
