<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Communication\ConversationMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly ConversationMessage $message
    ) {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.'.$this->message->id_conversation),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'id_conversation' => $this->message->id_conversation,
            'id_user' => $this->message->id_user,
            'message' => $this->message->message,
            'created_at' => $this->message->created_at,
            'user' => [
                'id' => $this->message->user?->id,
                'first_name' => $this->message->user?->first_name,
                'last_name' => $this->message->user?->last_name,
            ],
        ];
    }
}
