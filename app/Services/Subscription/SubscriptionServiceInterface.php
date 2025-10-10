<?php

namespace App\Services\Subscription;

use App\DTOs\Subscription\SubscriptionRequestDTO;
use App\DTOs\Subscription\MarketstackSubscriptionDTO;
use App\DTOs\Subscription\FeedOptionsDTO;
use App\Models\User;

/**
 * Subscription service interface
 */
interface SubscriptionServiceInterface
{
    /**
     * Set subscription status
     *
     * @param SubscriptionRequestDTO $dto
     * @param User $user
     * @return array
     */
    public function setSubscription(SubscriptionRequestDTO $dto, User $user): array;

    /**
     * Get feed list
     *
     * @param FeedOptionsDTO $dto
     * @param User|null $user
     * @return array
     */
    public function getFeedList(FeedOptionsDTO $dto, ?User $user): array;

    /**
     * Get feed categories
     *
     * @return array
     */
    public function getFeedCategories(): array;

    /**
     * Get satellite feed list
     *
     * @param FeedOptionsDTO $dto
     * @param User|null $user
     * @return array
     */
    public function getSatelliteFeedList(FeedOptionsDTO $dto, ?User $user): array;

    /**
     * Add marketstack subscription
     *
     * @param MarketstackSubscriptionDTO $dto
     * @param User $user
     * @return array
     */
    public function addMarketstackSubscription(MarketstackSubscriptionDTO $dto, User $user): array;

    /**
     * Get feed options
     *
     * @return array
     */
    public function getFeedOptions(): array;

    /**
     * Delete private feed
     *
     * @param int $id
     * @param User $user
     * @return array
     */
    public function deletePrivateFeed(int $id, User $user): array;
}
