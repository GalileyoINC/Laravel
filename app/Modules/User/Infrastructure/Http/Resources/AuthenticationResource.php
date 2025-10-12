<?php

declare(strict_types=1);

namespace App\Modules\System\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthenticationResource extends JsonResource
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
                'email' => $this->resource['email'] ?? null,
                'first_name' => $this->resource['first_name'] ?? null,
                'last_name' => $this->resource['last_name'] ?? null,
                'role' => $this->resource['role'] ?? null,
                'status' => $this->resource['status'] ?? null,
                'is_influencer' => $this->resource['is_influencer'] ?? false,
                'is_valid_email' => $this->resource['is_valid_email'] ?? false,
                'bonus_point' => $this->resource['bonus_point'] ?? 0,
                'image' => $this->resource['image'] ?? null,
                'timezone' => $this->resource['timezone'] ?? null,
                'auth_key' => $this->resource['auth_key'] ?? null,
                'access_token' => $this->resource['access_token'] ?? null,
                'refresh_token' => $this->resource['refresh_token'] ?? null,
                'expires_at' => $this->resource['expires_at'] ?? null,
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
