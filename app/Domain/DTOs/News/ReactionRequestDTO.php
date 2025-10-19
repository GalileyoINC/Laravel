<?php

declare(strict_types=1);

namespace App\Domain\DTOs\News;

use Illuminate\Http\Request;

/**
 * DTO for News Reaction requests
 */
class ReactionRequestDTO
{
    public function __construct(
        public readonly int $idNews,
        public readonly string $reactionType,
        public readonly ?string $message = null
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            idNews: (int) $data['id_news'],
            reactionType: $data['reaction_type'] ?? 'like',
            message: $data['message'] ?? null
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            idNews: $request->input('id_news'),
            reactionType: $request->input('reaction_type', 'like'),
            message: $request->input('message')
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id_news' => $this->idNews,
            'reaction_type' => $this->reactionType,
            'message' => $this->message,
        ];
    }

    public function validate(): bool
    {
        return $this->idNews > 0 && ! empty($this->reactionType);
    }
}
