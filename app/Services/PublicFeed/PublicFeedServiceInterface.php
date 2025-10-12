<?php

declare(strict_types=1);

namespace App\Services\PublicFeed;

use App\DTOs\PublicFeed\PublicFeedImageUploadRequestDTO;
use App\DTOs\PublicFeed\PublicFeedOptionsRequestDTO;
use App\DTOs\PublicFeed\PublicFeedPublishRequestDTO;
use App\Models\User\User;

/**
 * PublicFeed service interface
 */
interface PublicFeedServiceInterface
{
    /**
     * Get public feed options
     */
    public function getPublicFeedOptions(PublicFeedOptionsRequestDTO $dto, ?User $user): array;

    /**
     * Publish to public feeds
     */
    public function publishToPublicFeeds(PublicFeedPublishRequestDTO $dto, User $user): array;

    /**
     * Upload image for public feed
     */
    public function uploadImage(PublicFeedImageUploadRequestDTO $dto, User $user): array;
}
