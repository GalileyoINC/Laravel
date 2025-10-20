<?php

declare(strict_types=1);

namespace App\Domain\DTOs\News;

use Illuminate\Http\Request;

/**
 * DTO for News by Subscription requests
 */
class NewsBySubscriptionDTO
{
    public function __construct(
        public readonly int $idSubscription,
        public readonly ?int $limit = 20,
        public readonly ?int $offset = 0,
        public readonly ?string $type = null,
        public readonly ?string $search = null
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            idSubscription: (int) $data['id_subscription'],
            limit: isset($data['limit']) ? (int) $data['limit'] : 20,
            offset: isset($data['offset']) ? (int) $data['offset'] : 0,
            type: $data['type'] ?? null,
            search: $data['search'] ?? null
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            idSubscription: $request->input('id_subscription'),
            limit: $request->input('limit', 20),
            offset: $request->input('offset', 0),
            type: $request->input('type'),
            search: $request->input('search')
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
            'id_subscription' => $this->idSubscription,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'type' => $this->type,
            'search' => $this->search,
        ];
    }

    public function validate(): bool
    {
        return $this->idSubscription > 0 && $this->limit > 0 && $this->offset >= 0;
    }
}
