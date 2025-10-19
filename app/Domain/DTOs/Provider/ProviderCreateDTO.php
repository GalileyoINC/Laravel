<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Provider;

readonly class ProviderCreateDTO
{
    public function __construct(
        public string $name,
        public ?string $email,
        public ?string $country,
        public ?bool $isSatellite,
    ) {}
}
