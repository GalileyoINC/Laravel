<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Communication;

readonly class SendSmsRequestDTO
{
    public function __construct(
        public int $phoneNumberId,
        public string $message,
    ) {}
}
