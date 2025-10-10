<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => 'success',
            'data' => [
                'id' => $this->resource['id'] ?? null,
                'id_user' => $this->resource['id_user'] ?? null,
                'status' => $this->resource['status'] ?? null,
                'total_amount' => $this->resource['total_amount'] ?? null,
                'is_paid' => $this->resource['is_paid'] ?? false,
                'payment_method' => $this->resource['payment_method'] ?? null,
                'payment_details' => $this->resource['payment_details'] ?? [],
                'products' => $this->resource['products'] ?? [],
                'billing_address' => $this->resource['billing_address'] ?? null,
                'shipping_address' => $this->resource['shipping_address'] ?? null,
                'transaction_id' => $this->resource['transaction_id'] ?? null,
                'apple_transaction_id' => $this->resource['apple_transaction_id'] ?? null,
                'created_at' => $this->resource['created_at'] ?? null,
                'updated_at' => $this->resource['updated_at'] ?? null,
            ]
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'timestamp' => now()->toISOString(),
                'version' => '1.0',
            ],
        ];
    }
}