<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
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
                'uuid' => $this->resource['uuid'] ?? null,
                'os' => $this->resource['os'] ?? null,
                'push_token' => $this->resource['push_token'] ?? null,
                'access_token' => $this->resource['access_token'] ?? null,
                'params' => $this->resource['params'] ?? [],
                'push_turn_on' => $this->resource['push_turn_on'] ?? false,
                'device_model' => $this->resource['device_model'] ?? null,
                'os_version' => $this->resource['os_version'] ?? null,
                'app_version' => $this->resource['app_version'] ?? null,
                'screen_resolution' => $this->resource['screen_resolution'] ?? null,
                'timezone' => $this->resource['timezone'] ?? null,
                'language' => $this->resource['language'] ?? null,
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