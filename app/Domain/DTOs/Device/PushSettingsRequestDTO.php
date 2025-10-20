<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Device;

use Illuminate\Http\Request;

class PushSettingsRequestDTO
{
    public function __construct(
        public readonly bool $pushTurnOn,
        public readonly bool $isSend,
        public readonly bool $isEmergencyOnly
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            pushTurnOn: (bool) $data['push_turn_on'],
            isSend: (bool) $data['is_send'],
            isEmergencyOnly: (bool) $data['is_emergency_only']
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            pushTurnOn: $request->boolean('push_turn_on'),
            isSend: $request->boolean('is_send'),
            isEmergencyOnly: $request->boolean('is_emergency_only')
        );
    }

    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'push_turn_on' => $this->pushTurnOn,
            'is_send' => $this->isSend,
            'is_emergency_only' => $this->isEmergencyOnly,
        ];
    }

    public function validate(): bool
    {
        return true; // All fields are boolean, validation is implicit
    }
}
