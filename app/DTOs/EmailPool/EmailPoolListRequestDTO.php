<?php

declare(strict_types=1);

namespace App\DTOs\EmailPool;

readonly class EmailPoolListRequestDTO
{
    public function __construct(
        public int $page,
        public int $limit,
        public ?string $search,
        public ?string $status,
        public ?string $to
    ) {}
}
