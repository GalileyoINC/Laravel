<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Contact;

readonly class ContactListRequestDTO
{
    public function __construct(
        public int $page,
        public int $limit,
        public ?string $search,
        public int $status
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'limit' => $this->limit,
            'search' => $this->search,
            'status' => $this->status,
        ];
    }
}
