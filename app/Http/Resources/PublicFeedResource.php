<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicFeedResource extends JsonResource
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
                'uuid' => $this->resource['uuid'] ?? null,
                'text' => $this->resource['text'] ?? null,
                'text_short' => $this->resource['text_short'] ?? null,
                'url' => $this->resource['url'] ?? null,
                'is_link' => $this->resource['is_link'] ?? false,
                'subscriptions' => $this->resource['subscriptions'] ?? [],
                'images' => $this->resource['images'] ?? [],
                'published_at' => $this->resource['published_at'] ?? null,
                'author' => $this->resource['author'] ?? null,
                'reactions' => $this->resource['reactions'] ?? [],
                'comments_count' => $this->resource['comments_count'] ?? 0,
                'views_count' => $this->resource['views_count'] ?? 0,
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