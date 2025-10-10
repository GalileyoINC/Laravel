<?php

namespace App\DTOs\PrivateFeed;

use Illuminate\Http\Request;

class PrivateFeedListRequestDTO
{
    public function __construct(
        public readonly ?int $limit = 20,
        public readonly ?int $offset = 0,
        public readonly ?array $filter = []
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            limit: isset($data['limit']) ? (int)$data['limit'] : 20,
            offset: isset($data['offset']) ? (int)$data['offset'] : 0,
            filter: $data['filter'] ?? []
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            limit: $request->input('limit', 20),
            offset: $request->input('offset', 0),
            filter: $request->input('filter', [])
        );
    }

    public function toArray(): array
    {
        return [
            'limit' => $this->limit,
            'offset' => $this->offset,
            'filter' => $this->filter
        ];
    }

    public function validate(): bool
    {
        return $this->limit > 0 && $this->offset >= 0;
    }
}
