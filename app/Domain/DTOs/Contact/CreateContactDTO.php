<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Contact;

readonly class CreateContactDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $subject,
        public string $body,
        public ?int $idUser = null,
        public ?string $phone = null,
        public ?int $status = 1
    ) {}
}
