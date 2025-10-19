<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Communication\EmailPoolAttachment;

/**
 * @mixin \App\Models\Communication\EmailPool
 */
class EmailPoolResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'to' => $this->to,
            'from' => $this->from,
            'body' => $this->body,
            'body_plain' => $this->bodyPlain,
            'status' => $this->status,
            'sent_at' => $this->getAttribute('sent_at'),
            'failed_at' => $this->getAttribute('failed_at'),
            'error_message' => $this->getAttribute('error_message'),
            'attachments' => $this->whenLoaded('email_pool_attachments', fn (): array => $this->email_pool_attachments->map(fn (EmailPoolAttachment $attachment): array => [
                'id' => $attachment->id,
                'file_name' => $attachment->file_name,
                'content_type' => $attachment->content_type,
                'file_size' => $attachment->getAttribute('file_size'),
            ])->all()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
