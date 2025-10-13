<?php

declare(strict_types=1);

namespace App\Services\Chat;

use App\DTOs\Chat\ChatListRequestDTO;
use App\DTOs\Chat\ChatMessagesRequestDTO;
use App\Models\Communication\Conversation;
use App\Models\Communication\ConversationMessage;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Chat service implementation
 */
class ChatService implements ChatServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConversationList(ChatListRequestDTO $dto, User $user)
    {
        try {
            $query = Conversation::whereHas('users', function ($q) use ($user) {
                $q->where('id_user', $user->id);
            });

            // Apply filters if any
            if (! empty($dto->filter)) {
                // Add filter logic here
            }

            $conversations = $query->with(['users', 'conversation_messages' => function ($q) {
                $q->latest()->limit(1);
            }])
                ->orderBy('updated_at', 'desc')
                ->limit($dto->limit)
                ->offset($dto->offset)
                ->get();

            return $conversations;

        } catch (Exception $e) {
            Log::error('ChatService getConversationList error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConversationMessages(ChatMessagesRequestDTO $dto, User $user)
    {
        try {
            $conversation = Conversation::whereHas('users', function ($q) use ($user) {
                $q->where('id_user', $user->id);
            })->find($dto->conversationId);

            if (! $conversation) {
                throw new Exception('Conversation not found');
            }

            $messages = ConversationMessage::where('id_conversation', $dto->conversationId)
                ->with(['user'])
                ->orderBy('created_at', 'desc')
                ->limit($dto->limit)
                ->offset($dto->offset)
                ->get();

            return $messages;

        } catch (Exception $e) {
            Log::error('ChatService getConversationMessages error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConversationView(int $conversationId, User $user)
    {
        try {
            $conversation = Conversation::whereHas('users', function ($q) use ($user) {
                $q->where('id_user', $user->id);
            })->with(['users', 'conversation_messages.user'])
                ->find($conversationId);

            if (! $conversation) {
                throw new Exception('Conversation not found');
            }

            return $conversation;

        } catch (Exception $e) {
            Log::error('ChatService getConversationView error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function uploadFiles(array $files, int $conversationId, User $user)
    {
        // Implementation for file upload
        return ['message' => 'File upload not implemented yet'];
    }

    /**
     * {@inheritdoc}
     */
    public function createGroupChat(array $data, User $user)
    {
        // Implementation for creating group chat
        return ['message' => 'Group chat creation not implemented yet'];
    }

    /**
     * {@inheritdoc}
     */
    public function getFriendChat(int $friendId, User $user)
    {
        // Implementation for getting friend chat
        return ['message' => 'Friend chat not implemented yet'];
    }
}
