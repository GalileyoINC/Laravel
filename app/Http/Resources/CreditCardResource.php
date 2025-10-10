<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditCardResource extends JsonResource
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
                'first_name' => $this->resource['first_name'] ?? null,
                'last_name' => $this->resource['last_name'] ?? null,
                'num' => $this->resource['num'] ?? null,
                'cvv' => $this->resource['cvv'] ?? null,
                'type' => $this->resource['type'] ?? null,
                'expiration_year' => $this->resource['expiration_year'] ?? null,
                'expiration_month' => $this->resource['expiration_month'] ?? null,
                'is_active' => $this->resource['is_active'] ?? false,
                'is_preferred' => $this->resource['is_preferred'] ?? false,
                'anet_customer_payment_profile_id' => $this->resource['anet_customer_payment_profile_id'] ?? null,
                'anet_profile_deleted' => $this->resource['anet_profile_deleted'] ?? false,
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