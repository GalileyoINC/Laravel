<?php

namespace App\DTOs\AllSendForm;

use Illuminate\Http\Request;

class AllSendOptionsRequestDTO
{
    public function __construct()
    {}

    public static function fromArray(array $data): static
    {
        return new self();
    }

    public static function fromRequest(Request $request): static
    {
        return new self();
    }

    public function toArray(): array
    {
        return [];
    }
}
