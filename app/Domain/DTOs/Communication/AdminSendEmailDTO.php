<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Communication;

readonly class AdminSendEmailDTO
{
    public function __construct(
        public int $emailTemplateId,
        public string $toEmail,
        /** @var array<string, string> */
        public array $params = [],
    ) {}
}
