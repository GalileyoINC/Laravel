<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Communication\EmailTemplate
 */
class EmailTemplateResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'subject' => $this->subject,
            'body' => $this->body,
            'params' => $this->params ? (is_array($this->params) ? $this->params : (json_decode((string) $this->params, true) ?: [])) : [],
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
            0 => 'Inactive',
            default => 'Unknown'
        };
    }
}
