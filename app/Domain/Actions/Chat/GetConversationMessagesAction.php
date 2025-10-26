<?php

declare(strict_types=1);

namespace App\Domain\Actions\Chat;

use App\Models\Communication\ConversationMessage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetConversationMessagesAction
{
    public function execute(int $conversationId, int $perPage = 20, int $page = 1): LengthAwarePaginator
    {
        return ConversationMessage::where('id_conversation', $conversationId)
            ->with(['user:id,first_name,last_name'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }
}
