<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Phone;

use Illuminate\Http\Request;

class PhoneVerifyRequestDTO
{
    public function __construct(
        public readonly int $id
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            id: (int) $data['id']
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            id: $request->input('id')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    public function validate(): bool
    {
        return $this->id > 0;
    }
}
