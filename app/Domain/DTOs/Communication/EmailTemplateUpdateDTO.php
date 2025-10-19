<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Communication;

readonly class EmailTemplateUpdateDTO
{
    public function __construct(
        public int $id,
        public string $fromEmail,
        public string $fromName,
        public string $subject,
        public string $body,
        public ?string $bodyPlain = null,
    ) {}
}
