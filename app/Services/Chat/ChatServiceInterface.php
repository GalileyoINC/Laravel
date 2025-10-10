<?php

namespace App\Services\Chat;

use App\DTOs\Chat\ChatListRequestDTO;
use App\DTOs\Chat\ChatMessagesRequestDTO;
use App\Models\User;

/**
 * Chat service interface
 */
interface ChatServiceInterface
{
    /**
     * Get conversation list for user
     *
     * @param ChatListRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function getConversationList(ChatListRequestDTO $dto, User $user);

    /**
     * Get messages for a conversation
     *
     * @param ChatMessagesRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function getConversationMessages(ChatMessagesRequestDTO $dto, User $user);

    /**
     * Get single conversation view
     *
     * @param int $conversationId
     * @param User $user
     * @return mixed
     */
    public function getConversationView(int $conversationId, User $user);

    /**
     * Upload files to conversation
     *
     * @param array $files
     * @param int $conversationId
     * @param User $user
     * @return mixed
     */
    public function uploadFiles(array $files, int $conversationId, User $user);

    /**
     * Create group chat
     *
     * @param array $data
     * @param User $user
     * @return mixed
     */
    public function createGroupChat(array $data, User $user);

    /**
     * Get friend chat conversation
     *
     * @param int $friendId
     * @param User $user
     * @return mixed
     */
    public function getFriendChat(int $friendId, User $user);
}
