<?php

declare(strict_types=1);

namespace App\Domain\Actions\Chat;

use App\Models\Communication\ConversationMessage;
use Exception;
use Illuminate\Support\Facades\Auth;

class SendAdminMessageAction
{
    public function execute(int $conversationId, string $message): ConversationMessage
    {
        $user = Auth::user();

        if (! $user) {
            throw new Exception('User not authenticated');
        }

        $messageModel = ConversationMessage::create([
            'id_conversation' => $conversationId,
            'id_user' => $user->id,
            'message' => $message,
        ]);

        $messageModel->load('user:id,first_name,last_name');

        return $messageModel;
    }
}
