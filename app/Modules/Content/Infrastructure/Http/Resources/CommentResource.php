<?php

declare(strict_types=1);

namespace App\Modules\Content\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
                'id_sms_pool' => $this->resource['id_sms_pool'] ?? null,
                'id_user' => $this->resource['id_user'] ?? null,
                'message' => $this->resource['message'] ?? null,
                'id_parent' => $this->resource['id_parent'] ?? null,
                'is_deleted' => $this->resource['is_deleted'] ?? false,
                'user' => $this->resource['user'] ?? null,
                'replies' => $this->resource['replies'] ?? [],
                'reactions' => $this->resource['reactions'] ?? [],
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
