<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Device;

use Illuminate\Http\Request;

class DeviceVerifyRequestDTO
{
    public function __construct(
        public readonly string $uuid
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            uuid: $data['uuid']
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            uuid: $request->input('uuid')
        );
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
        ];
    }

    public function validate(): bool
    {
        return ! empty($this->uuid);
    }
}
