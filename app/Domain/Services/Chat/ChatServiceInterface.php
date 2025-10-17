<?php

declare(strict_types=1);

namespace App\Domain\Services\Chat;

use App\Domain\DTOs\Chat\ChatListRequestDTO;
use App\Domain\DTOs\Chat\ChatMessagesRequestDTO;
use App\Models\User\User;

/**
 * Chat service interface
 */
interface ChatServiceInterface
{
    /**
     * Get conversation list for user
     *
     * @return mixed
     */
    public function getConversationList(ChatListRequestDTO $dto, User $user);

    /**
     * Get messages for a conversation
     *
     * @return mixed
     */
    public function getConversationMessages(ChatMessagesRequestDTO $dto, User $user);

    /**
     * Get single conversation view
     *
     * @return mixed
     */
    public function getConversationView(int $conversationId, User $user);

    /**
     * Upload files to conversation
     *
     * @return mixed
     */
    public function uploadFiles(array $files, int $conversationId, User $user);

    /**
     * Create group chat
     *
     * @return mixed
     */
    public function createGroupChat(array $data, User $user);

    /**
     * Get friend chat conversation
     *
     * @return mixed
     */
    public function getFriendChat(int $friendId, User $user);
}
