<?php

declare(strict_types=1);

namespace App\Modules\Communication\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailTemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'subject' => $this->subject,
            'body' => $this->body,
            'params' => $this->params ? json_decode($this->params, true) : [],
            'status' => $this->status,
            'status_text' => $this->getStatusText(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getStatusText(): string
    {
        return match ($this->status) {
            1 => 'Active',
            0 => 'Inactive',
            default => 'Unknown'
        };
    }
}
