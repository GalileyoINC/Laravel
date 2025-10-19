<?php

declare(strict_types=1);

namespace App\Domain\DTOs\EmailTemplate;

readonly class EmailTemplateUpdateRequestDTO
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $subject,
        public ?string $body,
        public ?array $params,
        public ?int $status
    ) {}
}
