<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
                'slug' => $this->resource['slug'] ?? null,
                'image' => $this->resource['image'] ?? null,
                'status' => $this->resource['status'] ?? null,
                'params' => $this->resource['params'] ?? [],
                'category' => $this->resource['category'] ?? null,
                'priority' => $this->resource['priority'] ?? null,
                'tags' => $this->resource['tags'] ?? [],
                'author' => $this->resource['author'] ?? null,
                'source' => $this->resource['source'] ?? null,
                'read_time' => $this->resource['read_time'] ?? null,
                'content' => $this->resource['content'] ?? null,
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