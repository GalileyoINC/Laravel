<?php

declare(strict_types=1);

namespace App\DTOs\Order;

/**
 * DTO for Order responses
 */
class OrderResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $status,
        public readonly float $totalAmount,
        public readonly bool $isPaid,
        public readonly ?string $paymentMethod = null,
        public readonly ?string $createdAt = null,
        public readonly ?array $products = [],
        public readonly ?array $paymentDetails = []
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            id: (int) $data['id'],
            status: $data['status'] ?? 'pending',
            totalAmount: (float) $data['total_amount'],
            isPaid: (bool) $data['is_paid'],
            paymentMethod: $data['payment_method'] ?? null,
            createdAt: $data['created_at'] ?? null,
            products: $data['products'] ?? [],
            paymentDetails: $data['payment_details'] ?? []
        );
    }

    public static function fromModel($order): static
    {
        return new self(
            id: $order->id,
            status: $order->status ?? 'pending',
            totalAmount: (float) $order->total_amount,
            isPaid: (bool) $order->is_paid,
            paymentMethod: $order->payment_method,
            createdAt: $order->created_at?->format('Y-m-d H:i:s'),
            products: $order->products ?? [],
            paymentDetails: is_string($order->payment_details) ? json_decode($order->payment_details, true) ?? [] : ($order->payment_details ?? [])
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'total_amount' => $this->totalAmount,
            'is_paid' => $this->isPaid,
            'payment_method' => $this->paymentMethod,
            'created_at' => $this->createdAt,
            'products' => $this->products,
            'payment_details' => $this->paymentDetails,
        ];
    }
}
