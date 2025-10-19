<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Phone;

class PhoneVerifyResponseDTO
{
    public function __construct(
        public readonly string $message,
        public readonly string $status
    ) {}

    public static function fromData(string $message, string $status = 'sent'): static
    {
        /** @var static */
        return new self(
            message: $message,
            status: $status
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'status' => $this->status,
        ];
    }
}
