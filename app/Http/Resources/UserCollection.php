<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->collection->map(fn ($user) => [
            'id' => $user['id'] ?? null,
            'email' => $user['email'] ?? null,
            'first_name' => $user['first_name'] ?? null,
            'last_name' => $user['last_name'] ?? null,
            'role' => $user['role'] ?? null,
            'status' => $user['status'] ?? null,
            'is_influencer' => $user['is_influencer'] ?? false,
            'is_valid_email' => $user['is_valid_email'] ?? false,
            'bonus_point' => $user['bonus_point'] ?? 0,
            'image' => $user['image'] ?? null,
            'timezone' => $user['timezone'] ?? null,
            'created_at' => $user['created_at'] ?? null,
            'updated_at' => $user['updated_at'] ?? null,
        ]);

        $pagination = [
            'total' => null,
            'count' => $this->count(),
            'per_page' => null,
            'current_page' => null,
            'total_pages' => null,
        ];

        $resource = $this->resource;
        if ($resource instanceof LengthAwarePaginator) {
            $pagination = [
                'total' => $resource->total(),
                'count' => $resource->count(),
                'per_page' => $resource->perPage(),
                'current_page' => $resource->currentPage(),
                'total_pages' => $resource->lastPage(),
            ];
        }

        return [
            'status' => 'success',
            'data' => $data,
            'pagination' => $pagination,
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
