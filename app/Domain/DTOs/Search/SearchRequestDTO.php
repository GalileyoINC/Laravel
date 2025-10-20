<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Search;

use Illuminate\Http\Request;

/**
 * DTO for Search requests
 */
class SearchRequestDTO
{
    public function __construct(
        public readonly string $phrase,
        public readonly ?int $page = 1,
        public readonly ?int $pageSize = 10,
        public readonly ?string $type = null,
        /** @var array<string, mixed> */
        public readonly ?array $filters = []
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            phrase: $data['phrase'] ?? '',
            page: isset($data['page']) ? (int) $data['page'] : 1,
            pageSize: isset($data['page_size']) ? (int) $data['page_size'] : 10,
            type: $data['type'] ?? null,
            filters: $data['filters'] ?? []
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            phrase: $request->input('phrase', ''),
            page: $request->input('page', 1),
            pageSize: $request->input('page_size', 10),
            type: $request->input('type'),
            filters: $request->input('filters', [])
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
            'phrase' => $this->phrase,
            'page' => $this->page,
            'page_size' => $this->pageSize,
            'type' => $this->type,
            'filters' => $this->filters,
        ];
    }

    public function validate(): bool
    {
        return ! empty($this->phrase) &&
               mb_strlen($this->phrase) >= 3 &&
               $this->page > 0 &&
               $this->pageSize > 0 &&
               $this->pageSize <= 100;
    }
}
