<?php

declare(strict_types=1);

namespace App\Domain\DTOs\News;

class SetReactionRequestDTO
{
    public function __construct(
        public readonly int $newsId,
        public readonly string $reactionType,
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            newsId: (int) $data['news_id'],
            reactionType: (string) $data['reaction_type'],
        );
    }

    public function validate(): bool
    {
        return $this->newsId > 0 && ! empty($this->reactionType);
    }
}
