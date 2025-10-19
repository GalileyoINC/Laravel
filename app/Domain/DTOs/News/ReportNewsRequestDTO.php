<?php

declare(strict_types=1);

namespace App\Domain\DTOs\News;

class ReportNewsRequestDTO
{
    public function __construct(
        public string $id_news,
        public string $reason,
        public ?string $additional_text = null
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            id_news: $data['id_news'] ?? '',
            reason: $data['reason'] ?? '',
            additional_text: $data['additional_text'] ?? null
        );
    }

    public function validate(): bool
    {
        return ! empty($this->id_news) && ! empty($this->reason);
    }
}
