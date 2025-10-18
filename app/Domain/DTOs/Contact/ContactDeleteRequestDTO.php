<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Contact;

readonly class ContactDeleteRequestDTO
{
    public function __construct(
        public int $id
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
