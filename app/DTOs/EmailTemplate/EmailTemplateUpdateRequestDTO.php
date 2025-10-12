<?php

declare(strict_types=1);

namespace App\DTOs\EmailTemplate;

readonly class EmailTemplateUpdateRequestDTO
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $subject,
        public ?string $body,
        public ?array $params,
        public ?int $status
    ) {}
}
