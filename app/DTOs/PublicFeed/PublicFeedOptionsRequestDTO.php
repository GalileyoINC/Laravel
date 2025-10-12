<?php

declare(strict_types=1);

namespace App\DTOs\PublicFeed;

use Illuminate\Http\Request;

class PublicFeedOptionsRequestDTO
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
}
