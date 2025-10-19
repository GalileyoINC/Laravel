<?php

declare(strict_types=1);

namespace App\Domain\DTOs\PrivateFeed;

use Illuminate\Http\Request;

class PrivateFeedListRequestDTO
{
    /**
     * @param  array<string, mixed>  $filter
     */
    public function __construct(
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
            limit: isset($data['limit']) ? (int) $data['limit'] : 20,
            offset: isset($data['offset']) ? (int) $data['offset'] : 0,
            filter: $data['filter'] ?? []
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            limit: $request->input('limit', 20),
            offset: $request->input('offset', 0),
            filter: $request->input('filter', [])
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'limit' => $this->limit,
            'offset' => $this->offset,
            'filter' => $this->filter,
        ];
    }

    public function validate(): bool
    {
        return $this->limit > 0 && $this->offset >= 0;
    }
}
