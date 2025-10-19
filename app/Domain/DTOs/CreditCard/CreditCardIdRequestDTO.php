<?php

declare(strict_types=1);

namespace App\Domain\DTOs\CreditCard;

use Illuminate\Http\Request;

class CreditCardIdRequestDTO
{
    public function __construct(
        public readonly int $id
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            id: (int) $data['id']
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            id: $request->input('id')
        );
    }

    /**
     * @return array<string, mixed>
     */
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
