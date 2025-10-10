<?php

namespace App\DTOs\News;

use Illuminate\Http\Request;

/**
 * DTO for News List requests
 */
class NewsListRequestDTO
{
    public function __construct(
        public readonly ?string $type = null,
        public readonly ?int $limit = 20,
        public readonly ?int $offset = 0,
        public readonly ?string $search = null,
        public readonly ?array $filter = []
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            type: $data['type'] ?? null,
            limit: isset($data['limit']) ? (int)$data['limit'] : 20,
            offset: isset($data['offset']) ? (int)$data['offset'] : 0,
            search: $data['search'] ?? null,
            filter: $data['filter'] ?? []
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            type: $request->input('type'),
            limit: $request->input('limit', 20),
            offset: $request->input('offset', 0),
            search: $request->input('search'),
            filter: $request->input('filter', [])
        );
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'search' => $this->search,
            'filter' => $this->filter
        ];
    }

    public function validate(): bool
    {
        return $this->limit > 0 && $this->offset >= 0;
    }
}
