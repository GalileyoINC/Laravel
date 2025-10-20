<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrivateFeedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => 'success',
            'data' => [
                'id' => $this->resource['id'] ?? null,
                'id_user' => $this->resource['id_user'] ?? null,
                'name' => $this->resource['name'] ?? null,
                'description' => $this->resource['description'] ?? null,
                'token' => $this->resource['token'] ?? null,
                'image' => $this->resource['image'] ?? null,
                'is_active' => $this->resource['is_active'] ?? true,
                'followers_count' => $this->resource['followers_count'] ?? 0,
                'posts_count' => $this->resource['posts_count'] ?? 0,
                'settings' => $this->resource['settings'] ?? [],
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
    /**
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
