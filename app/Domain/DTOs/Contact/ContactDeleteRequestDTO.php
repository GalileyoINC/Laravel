<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Contact;

readonly class ContactDeleteRequestDTO
{
    public function __construct(
        public int $id
    ) {}

    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
