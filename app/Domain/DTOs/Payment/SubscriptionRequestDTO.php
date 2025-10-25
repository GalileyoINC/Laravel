<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Payment;

/**
 * SubscriptionRequestDTO
 * DTO for subscription management requests
 */
class SubscriptionRequestDTO
{
    public function __construct(
        public readonly int $product_id,
        public readonly int $credit_card_id,
        public readonly ?string $payment_method = 'authorize',
        public readonly ?string $external_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            product_id: $data['product_id'],
            credit_card_id: $data['credit_card_id'],
            payment_method: $data['payment_method'] ?? 'authorize',
            external_id: $data['external_id'] ?? null,
        );
    }

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'credit_card_id' => $this->credit_card_id,
            'payment_method' => $this->payment_method,
            'external_id' => $this->external_id,
        ];
    }
}
