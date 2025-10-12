<?php

declare(strict_types=1);

namespace App\DTOs\EmailTemplate;

readonly class EmailTemplateSendRequestDTO
{
    public function __construct(
        public int $id,
        public string $to,
        public ?string $subject,
        public ?string $body,
        public array $params
    ) {}
}
