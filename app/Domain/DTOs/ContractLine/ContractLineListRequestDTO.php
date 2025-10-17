<?php

declare(strict_types=1);

namespace App\Domain\DTOs\ContractLine;

readonly class ContractLineListRequestDTO
{
    public function __construct(
        public int $page,
        public int $limit,
        public ?string $search,
        public ?int $user_id,
        public ?int $service_id,
        public string $status
    ) {}
}
