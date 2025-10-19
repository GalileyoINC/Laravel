<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Customer;

use Illuminate\Http\Request;

class GetProfileRequestDTO
{
    public function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self;
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [];
    }

    public function validate(): bool
    {
        return true; // No validation needed for get profile
    }
}
