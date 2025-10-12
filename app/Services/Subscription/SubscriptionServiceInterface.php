<?php

declare(strict_types=1);

namespace App\Services\Subscription;

use App\DTOs\Subscription\FeedOptionsDTO;
use App\DTOs\Subscription\MarketstackSubscriptionDTO;
use App\DTOs\Subscription\SubscriptionRequestDTO;
use App\Models\User\User;

/**
 * Subscription service interface
 */
interface SubscriptionServiceInterface
{
    /**
     * Set subscription status
     */
    public function setSubscription(SubscriptionRequestDTO $dto, User $user): array;

    /**
     * Get feed list
     */
    public function getFeedList(FeedOptionsDTO $dto, ?User $user): array;

    /**
     * Get feed categories
     */
    public function getFeedCategories(): array;

    /**
     * Get satellite feed list
     */
    public function getSatelliteFeedList(FeedOptionsDTO $dto, ?User $user): array;

    /**
     * Add marketstack subscription
     */
    public function addMarketstackSubscription(MarketstackSubscriptionDTO $dto, User $user): array;

    /**
     * Get feed options
     */
    public function getFeedOptions(): array;

    /**
     * Delete private feed
     */
    public function deletePrivateFeed(int $id, User $user): array;
}
