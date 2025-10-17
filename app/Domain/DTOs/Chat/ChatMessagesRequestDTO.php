<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Chat;

use Illuminate\Http\Request;

class ChatMessagesRequestDTO
{
    public function __construct(
        public readonly int $conversationId,
        public readonly ?int $limit = 20,
        public readonly ?int $offset = 0,
        public readonly ?int $page = null,
        public readonly ?int $pageSize = null,
        public readonly ?string $list = null,
        public readonly ?int $count = null
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            conversationId: (int) $data['id'],
            limit: isset($data['limit']) ? (int) $data['limit'] : 20,
            offset: isset($data['offset']) ? (int) $data['offset'] : 0,
            page: isset($data['page']) ? (int) $data['page'] : null,
            pageSize: isset($data['page_size']) ? (int) $data['page_size'] : null,
            list: $data['list'] ?? null,
            count: isset($data['count']) ? (int) $data['count'] : null
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            conversationId: $request->input('id'),
            limit: $request->input('limit', 20),
            offset: $request->input('offset', 0),
            page: $request->input('page'),
            pageSize: $request->input('page_size'),
            list: $request->input('list'),
            count: $request->input('count')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->conversationId,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'page' => $this->page,
            'page_size' => $this->pageSize,
            'list' => $this->list,
            'count' => $this->count,
        ];
    }

    public function validate(): bool
    {
        return $this->conversationId > 0 && $this->limit > 0 && $this->offset >= 0;
    }
}
