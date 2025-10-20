<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Subscription;

use Illuminate\Http\Request;

/**
 * DTO for Marketstack subscription requests
 */
class MarketstackSubscriptionDTO
{
    public function __construct(
        public readonly string $type, // 'indx' or 'ticker'
        public readonly string $symbol,
        public readonly ?string $name = null,
        public readonly ?string $description = null
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            type: $data['type'] ?? '',
            symbol: $data['symbol'] ?? '',
            name: $data['name'] ?? null,
            description: $data['description'] ?? null
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            type: $request->input('type', ''),
            symbol: $request->input('symbol', ''),
            name: $request->input('name'),
            description: $request->input('description')
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
            'symbol' => $this->symbol,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    public function validate(): bool
    {
        return ! empty($this->symbol) && in_array($this->type, ['indx', 'ticker']);
    }
}
