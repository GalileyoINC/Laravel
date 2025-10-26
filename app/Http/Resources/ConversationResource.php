<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
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
            'users' => $this->whenLoaded('users', function () {
                return $this->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                    ];
                });
            }),
            'messages' => $this->whenLoaded('conversation_messages', function () {
                return $this->conversation_messages->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'id_user' => $message->id_user,
                        'message' => $message->message,
                        'created_at' => $message->created_at,
                        'user' => $message->user ? [
                            'id' => $message->user->id,
                            'first_name' => $message->user->first_name,
                            'last_name' => $message->user->last_name,
                        ] : null,
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
