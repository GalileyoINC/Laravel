<?php

declare(strict_types=1);

namespace App\Domain\DTOs\PublicFeed;

use Illuminate\Http\Request;

class PublicFeedOptionsRequestDTO
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
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [];
    }
}
