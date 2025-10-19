<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Communication\Contact
 */
class ContactResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->getAttribute('message') ?? $this->getAttribute('body'),
            'status' => $this->getAttribute('status'),
            'status_text' => $this->getStatusText(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getStatusText(): string
    {
        return match ((int) ($this->getAttribute('status') ?? -1)) {
            1 => 'Active',
            0 => 'Deleted',
            default => 'Unknown'
        };
    }
}
