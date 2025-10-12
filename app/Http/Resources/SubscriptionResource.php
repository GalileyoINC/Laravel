<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
                'id_subscription_category' => $this->resource['id_subscription_category'] ?? null,
                'name' => $this->resource['name'] ?? null,
                'title' => $this->resource['title'] ?? null,
                'position_no' => $this->resource['position_no'] ?? null,
                'description' => $this->resource['description'] ?? null,
                'rule' => $this->resource['rule'] ?? null,
                'alert_type' => $this->resource['alert_type'] ?? null,
                'is_active' => $this->resource['is_active'] ?? false,
                'is_hidden' => $this->resource['is_hidden'] ?? false,
                'percent' => $this->resource['percent'] ?? null,
                'price' => $this->resource['price'] ?? null,
                'bonus_point' => $this->resource['bonus_point'] ?? 0,
                'is_public' => $this->resource['is_public'] ?? false,
                'is_custom' => $this->resource['is_custom'] ?? false,
                'type' => $this->resource['type'] ?? null,
                'show_reactions' => $this->resource['show_reactions'] ?? true,
                'show_comments' => $this->resource['show_comments'] ?? true,
                'category' => $this->resource['category'] ?? null,
                'subscribers_count' => $this->resource['subscribers_count'] ?? 0,
                'posts_count' => $this->resource['posts_count'] ?? 0,
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
