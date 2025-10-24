<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Posts;

use Illuminate\Http\UploadedFile;

/**
 * Post create request DTO
 */
class PostCreateRequestDTO
{
    /**
     * @param  array<UploadedFile>  $media
     */
    public function __construct(
        public readonly string $content,
        public readonly array $media = [],
        public readonly ?int $userId = null
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            content: $data['content'] ?? '',
            media: $data['media'] ?? [],
            userId: $data['user_id'] ?? null
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'media' => $this->media,
            'user_id' => $this->userId,
        ];
    }
}
