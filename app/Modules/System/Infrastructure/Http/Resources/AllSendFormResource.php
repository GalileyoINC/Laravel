<?php

declare(strict_types=1);

namespace App\Modules\System\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllSendFormResource extends JsonResource
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
                'private_feeds' => $this->resource['private_feeds'] ?? [],
                'is_schedule' => $this->resource['is_schedule'] ?? false,
                'schedule' => $this->resource['schedule'] ?? null,
                'timezone' => $this->resource['timezone'] ?? null,
                'images' => $this->resource['images'] ?? [],
                'status' => $this->resource['status'] ?? 'pending',
                'sent_count' => $this->resource['sent_count'] ?? 0,
                'failed_count' => $this->resource['failed_count'] ?? 0,
                'author' => $this->resource['author'] ?? null,
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
