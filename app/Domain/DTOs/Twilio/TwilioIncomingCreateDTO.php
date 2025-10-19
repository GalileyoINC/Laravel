<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Twilio;

readonly class TwilioIncomingCreateDTO
{
    public function __construct(
        public string $number,
        public string $body,
        public ?string $message = null,
    ) {}
}
