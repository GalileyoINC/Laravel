<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Device;

use Illuminate\Http\Request;

class DeviceUpdateRequestDTO
{
    /**
     * @param  array<string, mixed>|null  $info
     */
    public function __construct(
        public readonly ?string $os = null,
        public readonly ?string $pushToken = null,
        public readonly ?array $info = []
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            os: $data['os'] ?? null,
            pushToken: $data['push_token'] ?? null,
            info: $data['info'] ?? []
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            os: $request->input('os'),
            pushToken: $request->input('push_token'),
            info: $request->input('info', [])
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'os' => $this->os,
            'push_token' => $this->pushToken,
            'info' => $this->info,
        ];
    }

    public function validate(): bool
    {
        return true; // All fields are optional
    }
}
