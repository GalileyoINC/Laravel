<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAlertMapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'status' => $this->status,
            'title' => $this->title,
            'description' => $this->description,
            'alert_data' => $this->alert_data,
            
            // Map-specific data
            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'address' => $this->address,
            ],
            
            'severity' => $this->severity,
            'category' => $this->category,
            'affected_radius' => $this->affected_radius,
            'source' => $this->source,
            'expires_at' => $this->expires_at?->toISOString(),
            
            // Timestamps
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Additional computed fields
            'is_active' => $this->expires_at === null || $this->expires_at > now(),
            'severity_level' => $this->getSeverityLevel(),
            'category_label' => $this->getCategoryLabel(),
        ];
    }

    /**
     * Get severity level as integer for sorting/filtering
     */
    private function getSeverityLevel(): int
    {
        return match ($this->severity) {
            'critical' => 4,
            'high' => 3,
            'medium' => 2,
            'low' => 1,
            default => 0,
        };
    }

    /**
     * Get human-readable category label
     */
    private function getCategoryLabel(): string
    {
        return match ($this->category) {
            'emergency' => 'Emergency Alert',
            'weather' => 'Weather Alert',
            'security' => 'Security Alert',
            'traffic' => 'Traffic Alert',
            'medical' => 'Medical Alert',
            'fire' => 'Fire Alert',
            'police' => 'Police Alert',
            default => ucfirst($this->category ?? 'Unknown'),
        };
    }
}
