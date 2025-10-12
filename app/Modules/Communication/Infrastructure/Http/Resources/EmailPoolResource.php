<?php

declare(strict_types=1);

namespace App\Modules\Communication\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailPoolResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'to' => $this->to,
            'from' => $this->from,
            'body' => $this->body,
            'body_plain' => $this->body_plain,
            'status' => $this->status,
            'sent_at' => $this->sent_at,
            'failed_at' => $this->failed_at,
            'error_message' => $this->error_message,
            'attachments' => $this->whenLoaded('attachments', fn () => $this->attachments->map(fn ($attachment) => [
                'id' => $attachment->id,
                'file_name' => $attachment->file_name,
                'content_type' => $attachment->content_type,
                'file_size' => $attachment->file_size,
            ])),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
