<?php

declare(strict_types=1);

namespace App\Domain\Actions\Chat;

use App\Models\Communication\ConversationMessage;
use Illuminate\Support\Collection;

class GetAdminRecentMessagesAction
{
    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function execute(int $limit = 50): Collection
    {
        return ConversationMessage::with('user:id,first_name,last_name')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'id_conversation' => $message->id_conversation,
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
    }
}
