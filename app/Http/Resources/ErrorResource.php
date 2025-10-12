<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => 'error',
            'error' => [
                'message' => $this->resource['message'] ?? 'An error occurred',
                'code' => $this->resource['code'] ?? 500,
                'details' => $this->resource['details'] ?? null,
                'validation_errors' => $this->resource['validation_errors'] ?? null,
                'trace_id' => $this->resource['trace_id'] ?? null,
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
