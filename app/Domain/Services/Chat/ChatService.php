<?php

declare(strict_types=1);

namespace App\Domain\Services\Chat;

use App\Domain\DTOs\Chat\ChatMessagesRequestDTO;
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
    public function getConversationList(int $page, int $limit, ?string $search, string $sortBy, string $sortOrder, User $user)
    {
        try {
            $query = Conversation::whereHas('users', function ($q) use ($user) {
                $q->where('id_user', $user->id);
            });

            // Apply search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                        ->orWhereHas('users', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', '%'.$search.'%')
                                ->orWhere('last_name', 'like', '%'.$search.'%');
                        });
                });
            }

            $conversations = $query->with(['users', 'conversation_messages' => function ($q) {
                $q->latest()->limit(1);
            }])
                ->orderBy($sortBy, $sortOrder)
                ->limit($limit)
                ->offset(($page - 1) * $limit)
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
                ->limit($dto->limit ?? 20)
                ->offset($dto->offset ?? 0)
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
