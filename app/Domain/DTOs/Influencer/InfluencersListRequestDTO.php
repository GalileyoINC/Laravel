<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Influencer;

/**
 * Influencers list request DTO
 */
class InfluencersListRequestDTO
{
    public function __construct(
        public readonly int $limit = 50,
        public readonly int $offset = 0,
        public readonly ?string $search = null,
        public readonly bool $verifiedOnly = true
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            limit: $data['limit'] ?? 50,
            offset: $data['offset'] ?? 0,
            search: $data['search'] ?? null,
            verifiedOnly: $data['verified_only'] ?? true
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
            'search' => $this->search,
            'verified_only' => $this->verifiedOnly,
        ];
    }
}
