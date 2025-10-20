<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
                'id_staff' => $this->resource['id_staff'] ?? null,
                'name' => $this->resource['name'] ?? null,
                'type' => $this->resource['type'] ?? null,
                'status' => $this->resource['status'] ?? null,
                'last_message' => $this->resource['last_message'] ?? null,
                'last_message_time' => $this->resource['last_message_time'] ?? null,
                'unread_count' => $this->resource['unread_count'] ?? 0,
                'participants' => $this->resource['participants'] ?? [],
                'messages' => $this->resource['messages'] ?? [],
                'files' => $this->resource['files'] ?? [],
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
