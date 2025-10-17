<?php

declare(strict_types=1);

namespace App\Domain\Services\PublicFeed;

use App\Domain\DTOs\PublicFeed\PublicFeedImageUploadRequestDTO;
use App\Domain\DTOs\PublicFeed\PublicFeedOptionsRequestDTO;
use App\Domain\DTOs\PublicFeed\PublicFeedPublishRequestDTO;
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
