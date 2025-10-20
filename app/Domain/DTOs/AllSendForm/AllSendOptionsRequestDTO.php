<?php

declare(strict_types=1);

namespace App\Domain\DTOs\AllSendForm;

use Illuminate\Http\Request;

class AllSendOptionsRequestDTO
{
    public function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self;
    }

    public static function fromRequest(Request $request): self
    {
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
