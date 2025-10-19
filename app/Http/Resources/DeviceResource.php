<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Device\Device
 */
class DeviceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->id_user,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => data_get($this, 'user.id'),
                'first_name' => data_get($this, 'user.first_name'),
                'last_name' => data_get($this, 'user.last_name'),
                'email' => data_get($this, 'user.email'),
            ]),
            'device_uuid' => $this->getAttribute('device_uuid') ?? $this->getAttribute('uuid'),
            'device_name' => $this->getAttribute('device_name'),
            'os' => $this->getAttribute('os'),
            'os_version' => $this->getAttribute('os_version'),
            'app_version' => $this->getAttribute('app_version'),
            'push_token' => $this->getAttribute('push_token'),
            'is_active' => $this->getAttribute('is_active'),
            'last_seen' => $this->getAttribute('last_seen'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
