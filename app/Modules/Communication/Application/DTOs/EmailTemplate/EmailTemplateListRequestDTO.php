<?php

declare(strict_types=1);

namespace App\DTOs\EmailTemplate;

readonly class EmailTemplateListRequestDTO
{
    public function __construct(
        public int $page,
        public int $limit,
        public ?string $search,
        public ?int $status
    ) {}
}
