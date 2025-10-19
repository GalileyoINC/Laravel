<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Users;

class UpdateUserDTO
{
    public function __construct(
        public int $userId,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $country,
        public ?string $state = null,
        public ?string $zip = null,
        public ?string $city = null,
        public ?int $role = null,
        public ?int $status = null,
        public ?string $password = null,
    ) {}
}
