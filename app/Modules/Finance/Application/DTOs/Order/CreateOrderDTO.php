<?php

declare(strict_types=1);

namespace App\Modules\Finance\Application\DTOs\Order;

use Illuminate\Http\Request;

/**
 * DTO for Order creation requests
 */
class CreateOrderDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity,
        public readonly string $paymentMethod,
        public readonly ?float $totalAmount = null,
        public readonly ?string $notes = null,
        public readonly ?array $productDetails = []
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            productId: (int) $data['product_id'],
            quantity: (int) $data['quantity'],
            paymentMethod: $data['payment_method'] ?? 'credit_card',
            totalAmount: isset($data['total_amount']) ? (float) $data['total_amount'] : null,
            notes: $data['notes'] ?? null,
            productDetails: $data['product_details'] ?? []
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            productId: $request->input('product_id'),
            quantity: $request->input('quantity'),
            paymentMethod: $request->input('payment_method', 'credit_card'),
            totalAmount: $request->input('total_amount'),
            notes: $request->input('notes'),
            productDetails: $request->input('product_details', [])
        );
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'payment_method' => $this->paymentMethod,
            'total_amount' => $this->totalAmount,
            'notes' => $this->notes,
            'product_details' => $this->productDetails,
        ];
    }

    public function validate(): bool
    {
        return $this->productId > 0 && $this->quantity > 0 && ! empty($this->paymentMethod);
    }
}
