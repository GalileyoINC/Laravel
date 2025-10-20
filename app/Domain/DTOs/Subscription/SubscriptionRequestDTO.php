<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Subscription;

use Illuminate\Http\Request;

/**
 * DTO for Subscription requests
 */
class SubscriptionRequestDTO
{
    public function __construct(
        public readonly int $idSubscription,
        public readonly bool $checked,
        public readonly ?string $zip = null,
        public readonly ?string $subType = 'regular' // 'regular' or 'satellite'
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            idSubscription: (int) $data['id'],
            checked: (bool) $data['checked'],
            zip: $data['zip'] ?? null,
            subType: $data['sub_type'] ?? 'regular'
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            idSubscription: $request->input('id'),
            checked: $request->boolean('checked'),
            zip: $request->input('zip'),
            subType: $request->input('sub_type', 'regular')
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
            'checked' => $this->checked,
            'zip' => $this->zip,
            'sub_type' => $this->subType,
        ];
    }

    public function validate(): bool
    {
        return $this->idSubscription > 0;
    }
}
