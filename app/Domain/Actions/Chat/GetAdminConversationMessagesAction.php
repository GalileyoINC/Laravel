<?php

declare(strict_types=1);

namespace App\Domain\Actions\Chat;

use App\Models\Communication\ConversationMessage;
use Illuminate\Database\Eloquent\Collection;

class GetAdminConversationMessagesAction
{
    /**
     * @return Collection<int, ConversationMessage>
     */
    public function execute(int $conversationId): Collection
    {
        return ConversationMessage::where('id_conversation', $conversationId)
            ->with('user:id,first_name,last_name')
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
