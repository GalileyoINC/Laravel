<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Phone;

use Illuminate\Http\Request;

class PhoneUpdateRequestDTO
{
    public function __construct(
        public readonly int $id,
        public readonly bool $isSend,
        public readonly bool $isEmergencyOnly
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            id: (int) $data['id'],
            isSend: (bool) $data['is_send'],
            isEmergencyOnly: (bool) $data['is_emergency_only']
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            id: $request->input('id'),
            isSend: $request->boolean('is_send'),
            isEmergencyOnly: $request->boolean('is_emergency_only')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'is_send' => $this->isSend,
            'is_emergency_only' => $this->isEmergencyOnly,
        ];
    }

    public function validate(): bool
    {
        return $this->id > 0;
    }
}
