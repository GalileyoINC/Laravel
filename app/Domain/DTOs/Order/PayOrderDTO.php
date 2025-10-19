<?php

declare(strict_types=1);

namespace App\Domain\DTOs\Order;

use Illuminate\Http\Request;

/**
 * DTO for Order payment requests
 */
class PayOrderDTO
{
    /**
     * @param  array<string, mixed>  $paymentDetails
     */
    public function __construct(
        public readonly int $idOrder,
        public readonly int $idCreditCard,
        public readonly ?string $paymentReference = null,
        public readonly ?array $paymentDetails = []
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            idOrder: (int) $data['id_order'],
            idCreditCard: (int) $data['id_credit_card'],
            paymentReference: $data['payment_reference'] ?? null,
            paymentDetails: $data['payment_details'] ?? []
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            idOrder: $request->input('id_order'),
            idCreditCard: $request->input('id_credit_card'),
            paymentReference: $request->input('payment_reference'),
            paymentDetails: $request->input('payment_details', [])
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id_order' => $this->idOrder,
            'id_credit_card' => $this->idCreditCard,
            'payment_reference' => $this->paymentReference,
            'payment_details' => $this->paymentDetails,
        ];
    }

    public function validate(): bool
    {
        return $this->idOrder > 0 && $this->idCreditCard > 0;
    }
}
