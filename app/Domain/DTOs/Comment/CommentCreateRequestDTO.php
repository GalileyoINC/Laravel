<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Comment;

use Illuminate\Http\Request;

class CommentCreateRequestDTO
{
    public function __construct(
        public readonly ?int $newsId,
        public readonly ?int $parentId,
        public readonly string $message
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            newsId: isset($data['id_news']) ? (int) $data['id_news'] : null,
            parentId: isset($data['id_parent']) ? (int) $data['id_parent'] : null,
            message: $data['message']
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            newsId: $request->input('id_news'),
            parentId: $request->input('id_parent'),
            message: $request->input('message')
        );
    }

    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id_news' => $this->newsId,
            'id_parent' => $this->parentId,
            'message' => $this->message,
        ];
    }

    public function validate(): bool
    {
        if (empty($this->message)) {
            return false;
        }

        if (! isset($this->newsId) && ! isset($this->parentId)) {
            return false;
        }

        return true;
    }
}
