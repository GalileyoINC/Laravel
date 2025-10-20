<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Comment;

use Illuminate\Http\Request;

class CommentListRequestDTO
{
    /**
     * @param  array<string, mixed>  $filter
     */
    public function __construct(
        public readonly int $newsId,
        public readonly ?int $limit = 20,
        public readonly ?int $offset = 0,
        public readonly ?array $filter = []
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            newsId: (int) $data['id_news'],
            limit: isset($data['limit']) ? (int) $data['limit'] : 20,
            offset: isset($data['offset']) ? (int) $data['offset'] : 0,
            filter: $data['filter'] ?? []
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            newsId: $request->input('id_news'),
            limit: $request->input('limit', 20),
            offset: $request->input('offset', 0),
            filter: $request->input('filter', [])
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
            'limit' => $this->limit,
            'offset' => $this->offset,
            'filter' => $this->filter,
        ];
    }

    public function validate(): bool
    {
        return $this->newsId > 0 && $this->limit > 0 && $this->offset >= 0;
    }
}
