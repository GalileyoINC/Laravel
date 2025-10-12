<?php

declare(strict_types=1);

namespace App\Modules\User\Application\DTOs\Customer;

use Illuminate\Http\Request;

class GetProfileRequestDTO
{
    public function __construct() {}

    public static function fromArray(array $data): static
    {
        return new self;
    }

    public static function fromRequest(Request $request): static
    {
        return new self;
    }

    public function toArray(): array
    {
        return [];
    }

    public function validate(): bool
    {
        return true; // No validation needed for get profile
    }
}
