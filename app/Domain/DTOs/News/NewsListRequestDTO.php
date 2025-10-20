<?php

declare(strict_types=1);

namespace App\Domain\DTOs\News;

use Illuminate\Http\Request;

/**
 * DTO for News List requests
 */
class NewsListRequestDTO
{
    /**
     * @param  array<string, mixed>  $filter
     */
    public function __construct(
        public readonly ?string $type = null,
        public readonly ?int $limit = 20,
        public readonly ?int $offset = 0,
        public readonly ?string $search = null,
        public readonly ?array $filter = []
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            type: $data['type'] ?? null,
            limit: isset($data['limit']) ? (int) $data['limit'] : 20,
            offset: isset($data['offset']) ? (int) $data['offset'] : 0,
            search: $data['search'] ?? null,
            filter: $data['filter'] ?? []
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            type: $request->input('type'),
            limit: $request->input('limit', 20),
            offset: $request->input('offset', 0),
            search: $request->input('search'),
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
            'type' => $this->type,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'search' => $this->search,
            'filter' => $this->filter,
        ];
    }

    public function validate(): bool
    {
        return $this->limit > 0 && $this->offset >= 0;
    }
}
