<?php

declare(strict_types=1);

namespace App\Domain\DTOs\EmailTemplate;

readonly class EmailTemplateSendRequestDTO
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function __construct(
        public int $id,
        public string $to,
        public ?string $subject,
        public ?string $body,
        public array $params
    ) {}
}
