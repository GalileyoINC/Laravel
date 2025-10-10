<?php

namespace App\Services\PublicFeed;

use App\DTOs\PublicFeed\PublicFeedOptionsRequestDTO;
use App\DTOs\PublicFeed\PublicFeedPublishRequestDTO;
use App\DTOs\PublicFeed\PublicFeedImageUploadRequestDTO;
use App\Models\User;

/**
 * PublicFeed service interface
 */
interface PublicFeedServiceInterface
{
    /**
     * Get public feed options
     *
     * @param PublicFeedOptionsRequestDTO $dto
     * @param User|null $user
     * @return array
     */
    public function getPublicFeedOptions(PublicFeedOptionsRequestDTO $dto, ?User $user): array;

    /**
     * Publish to public feeds
     *
     * @param PublicFeedPublishRequestDTO $dto
     * @param User $user
     * @return array
     */
    public function publishToPublicFeeds(PublicFeedPublishRequestDTO $dto, User $user): array;

    /**
     * Upload image for public feed
     *
     * @param PublicFeedImageUploadRequestDTO $dto
     * @param User $user
     * @return array
     */
    public function uploadImage(PublicFeedImageUploadRequestDTO $dto, User $user): array;
}
