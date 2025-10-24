<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Posts;

/**
 * Post update request DTO
 */
class PostUpdateRequestDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $content,
        public readonly ?int $userId = null
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            content: $data['content'] ?? '',
            userId: $data['user_id'] ?? null
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'user_id' => $this->userId,
        ];
    }
}
