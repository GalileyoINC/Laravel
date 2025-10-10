<?php

namespace App\DTOs\Phone;

class PhoneVerifyResponseDTO
{
    public function __construct(
        public readonly string $message,
        public readonly string $status
    ) {}

    public static function fromData(string $message, string $status = 'sent'): static
    {
        return new self(
            message: $message,
            status: $status
        );
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'status' => $this->status
        ];
    }
}
