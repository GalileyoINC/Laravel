<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Subscription;

use Illuminate\Http\Request;

/**
 * DTO for Feed options requests
 */
class FeedOptionsDTO
{
    public function __construct(
        public readonly ?string $category = null,
        public readonly ?int $limit = 20,
        public readonly ?int $offset = 0,
        /** @var array<string, mixed> */
        public readonly ?array $filters = []
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            category: $data['category'] ?? null,
            limit: isset($data['limit']) ? (int) $data['limit'] : 20,
            offset: isset($data['offset']) ? (int) $data['offset'] : 0,
            filters: $data['filters'] ?? []
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            category: $request->input('category'),
            limit: $request->input('limit', 20),
            offset: $request->input('offset', 0),
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
            'category' => $this->category,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'filters' => $this->filters,
        ];
    }

    public function validate(): bool
    {
        return $this->limit > 0 && $this->offset >= 0;
    }
}
