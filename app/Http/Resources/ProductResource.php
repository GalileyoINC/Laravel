<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
                'name' => $this->resource['name'] ?? null,
                'description' => $this->resource['description'] ?? null,
                'price' => $this->resource['price'] ?? null,
                'currency' => $this->resource['currency'] ?? 'USD',
                'type' => $this->resource['type'] ?? null,
                'category' => $this->resource['category'] ?? null,
                'is_active' => $this->resource['is_active'] ?? true,
                'is_featured' => $this->resource['is_featured'] ?? false,
                'images' => $this->resource['images'] ?? [],
                'alerts' => $this->resource['alerts'] ?? [],
                'apple_product_id' => $this->resource['apple_product_id'] ?? null,
                'google_product_id' => $this->resource['google_product_id'] ?? null,
                'subscription_duration' => $this->resource['subscription_duration'] ?? null,
                'trial_period' => $this->resource['trial_period'] ?? null,
                'created_at' => $this->resource['created_at'] ?? null,
                'updated_at' => $this->resource['updated_at'] ?? null,
            ],
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
