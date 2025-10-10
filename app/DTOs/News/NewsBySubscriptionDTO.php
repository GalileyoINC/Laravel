<?php

namespace App\DTOs\News;

use Illuminate\Http\Request;

/**
 * DTO for News by Subscription requests
 */
class NewsBySubscriptionDTO
{
    public function __construct(
        public readonly int $idSubscription,
        public readonly ?int $limit = 20,
        public readonly ?int $offset = 0
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            idSubscription: (int)$data['id_subscription'],
            limit: isset($data['limit']) ? (int)$data['limit'] : 20,
            offset: isset($data['offset']) ? (int)$data['offset'] : 0
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            idSubscription: $request->input('id_subscription'),
            limit: $request->input('limit', 20),
            offset: $request->input('offset', 0)
        );
    }

    public function toArray(): array
    {
        return [
            'id_subscription' => $this->idSubscription,
            'limit' => $this->limit,
            'offset' => $this->offset
        ];
    }

    public function validate(): bool
    {
        return $this->idSubscription > 0 && $this->limit > 0 && $this->offset >= 0;
    }
}
