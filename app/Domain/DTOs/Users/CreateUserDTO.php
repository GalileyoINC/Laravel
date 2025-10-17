<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Users;

readonly class CreateUserDTO
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password,
        public ?string $country = null,
        public ?string $zip = null,
        public ?string $state = null,
        public ?string $city = null,
        public int $role = 1,
        public int $status = 1
    ) {}
}
