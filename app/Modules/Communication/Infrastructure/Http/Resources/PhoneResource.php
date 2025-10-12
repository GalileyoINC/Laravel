<?php

declare(strict_types=1);

namespace App\Modules\Communication\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhoneResource extends JsonResource
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
                'id_user' => $this->resource['id_user'] ?? null,
                'phone_number' => $this->resource['phone_number'] ?? null,
                'is_verified' => $this->resource['is_verified'] ?? false,
                'verification_code' => $this->resource['verification_code'] ?? null,
                'verification_attempts' => $this->resource['verification_attempts'] ?? 0,
                'verification_expires_at' => $this->resource['verification_expires_at'] ?? null,
                'is_primary' => $this->resource['is_primary'] ?? false,
                'carrier' => $this->resource['carrier'] ?? null,
                'country_code' => $this->resource['country_code'] ?? null,
                'timezone' => $this->resource['timezone'] ?? null,
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
