<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Bookmark;

use Illuminate\Http\Request;

/**
 * DTO for Bookmark list requests
 */
class BookmarkListRequestDTO
{
    public function __construct(
        public readonly ?int $page = 1,
        public readonly ?int $pageSize = 10,
        public readonly ?string $type = null
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            page: isset($data['page']) ? (int) $data['page'] : 1,
            pageSize: isset($data['page_size']) ? (int) $data['page_size'] : 10,
            type: $data['type'] ?? null
        );
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            page: $request->input('page', 1),
            pageSize: $request->input('page_size', 10),
            type: $request->input('type')
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
            'page' => $this->page,
            'page_size' => $this->pageSize,
            'type' => $this->type,
        ];
    }

    public function validate(): bool
    {
        return $this->page > 0 &&
               $this->pageSize > 0 &&
               $this->pageSize <= 100;
    }
}
