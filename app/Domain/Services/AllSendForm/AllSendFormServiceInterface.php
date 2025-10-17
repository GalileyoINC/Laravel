<?php

declare(strict_types=1);

namespace App\Domain\Services\AllSendForm;

use App\Domain\DTOs\AllSendForm\AllSendBroadcastRequestDTO;
use App\Domain\DTOs\AllSendForm\AllSendImageUploadRequestDTO;
use App\Domain\DTOs\AllSendForm\AllSendOptionsRequestDTO;
use App\Models\User\User;

/**
 * AllSendForm service interface
 */
interface AllSendFormServiceInterface
{
    /**
     * Get all send options
     */
    public function getAllSendOptions(AllSendOptionsRequestDTO $dto, ?User $user): array;

    /**
     * Send broadcast message
     */
    public function sendBroadcast(AllSendBroadcastRequestDTO $dto, User $user): array;

    /**
     * Upload image for broadcast
     */
    public function uploadImage(AllSendImageUploadRequestDTO $dto, User $user): array;

    /**
     * Delete image
     */
    public function deleteImage(int $imageId, User $user): array;
}
