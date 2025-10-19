<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Users;

class ExportUsersRequestDTO
{
    public function __construct(
        public ?string $search = null,
        public ?int $status = null,
        public ?string $role = null,
        public bool $validEmailOnly = false,
    ) {}
}
