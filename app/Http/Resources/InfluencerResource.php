<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InfluencerResource extends JsonResource
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
                'id_subscription' => $this->resource['id_subscription'] ?? null,
                'title' => $this->resource['title'] ?? null,
                'alias' => $this->resource['alias'] ?? null,
                'description' => $this->resource['description'] ?? null,
                'image' => $this->resource['image'] ?? null,
                'subscription' => $this->resource['subscription'] ?? null,
                'feed_list' => $this->resource['feed_list'] ?? [],
                'followers_count' => $this->resource['followers_count'] ?? 0,
                'posts_count' => $this->resource['posts_count'] ?? 0,
                'is_active' => $this->resource['is_active'] ?? true,
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