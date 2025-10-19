<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Bookmark;

use Illuminate\Http\Request;

/**
 * DTO for Bookmark requests
 */
class BookmarkRequestDTO
{
    public function __construct(
        public readonly string $postId,
        public readonly ?int $page = 1,
        public readonly ?int $pageSize = 10
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            postId: $data['post_id'] ?? '',
            page: isset($data['page']) ? (int) $data['page'] : 1,
            pageSize: isset($data['page_size']) ? (int) $data['page_size'] : 10
        );
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            postId: $request->input('post_id', ''),
            page: $request->input('page', 1),
            pageSize: $request->input('page_size', 10)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'post_id' => $this->postId,
            'page' => $this->page,
            'page_size' => $this->pageSize,
        ];
    }

    public function validate(): bool
    {
        return ! empty($this->postId) &&
               $this->page > 0 &&
               $this->pageSize > 0 &&
               $this->pageSize <= 100;
    }
}
