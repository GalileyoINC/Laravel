<?php

namespace App\Services\AllSendForm;

use App\DTOs\AllSendForm\AllSendOptionsRequestDTO;
use App\DTOs\AllSendForm\AllSendBroadcastRequestDTO;
use App\DTOs\AllSendForm\AllSendImageUploadRequestDTO;
use App\Models\User;

/**
 * AllSendForm service interface
 */
interface AllSendFormServiceInterface
{
    /**
     * Get all send options
     *
     * @param AllSendOptionsRequestDTO $dto
     * @param User|null $user
     * @return array
     */
    public function getAllSendOptions(AllSendOptionsRequestDTO $dto, ?User $user): array;

    /**
     * Send broadcast message
     *
     * @param AllSendBroadcastRequestDTO $dto
     * @param User $user
     * @return array
     */
    public function sendBroadcast(AllSendBroadcastRequestDTO $dto, User $user): array;

    /**
     * Upload image for broadcast
     *
     * @param AllSendImageUploadRequestDTO $dto
     * @param User $user
     * @return array
     */
    public function uploadImage(AllSendImageUploadRequestDTO $dto, User $user): array;

    /**
     * Delete image
     *
     * @param int $imageId
     * @param User $user
     * @return array
     */
    public function deleteImage(int $imageId, User $user): array;
}
