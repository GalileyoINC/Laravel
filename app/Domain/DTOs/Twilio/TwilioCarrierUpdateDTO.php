<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Twilio;

readonly class TwilioCarrierUpdateDTO
{
    public function __construct(
        public int $carrierId,
        public int $providerId,
    ) {}
}
