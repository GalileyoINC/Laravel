<?php

declare(strict_types=1);

namespace App\Domain\Actions\Chat;

use App\Models\Communication\Conversation;

class GetConversationAction
{
    public function execute(int $conversationId): ?Conversation
    {
        return Conversation::with([
            'users:id,first_name,last_name',
            'conversation_messages' => function ($q) {
                $q->latest()->limit(20);
            },
            'conversation_messages.user:id,first_name,last_name',
        ])->find($conversationId);
    }
}
