<?php

declare(strict_types=1);

namespace App\Domain\Services\Subscription;

use App\Domain\DTOs\Subscription\FeedOptionsDTO;
use App\Domain\DTOs\Subscription\MarketstackSubscriptionDTO;
use App\Domain\DTOs\Subscription\SubscriptionRequestDTO;
use App\Models\User\User;

/**
 * Subscription service interface
 */
interface SubscriptionServiceInterface
{
    /**
     * Set subscription status
     *
     * @return array<string, mixed>
     */
    public function setSubscription(SubscriptionRequestDTO $dto, User $user): array;

    /**
     * Get feed list
     *
     * @return array<string, mixed>
     */
    public function getFeedList(FeedOptionsDTO $dto, ?User $user): array;

    /**
     * Get feed categories
     *
     * @return array<string, mixed>
     */
    public function getFeedCategories(): array;

    /**
     * Get satellite feed list
     *
     * @return array<string, mixed>
     */
    public function getSatelliteFeedList(FeedOptionsDTO $dto, ?User $user): array;

    /**
     * Add marketstack subscription
     *
     * @return array<string, mixed>
     */
    public function addMarketstackSubscription(MarketstackSubscriptionDTO $dto, User $user): array;

    /**
     * Get feed options
     *
     * @return array<string, mixed>
     */
    public function getFeedOptions(): array;

    /**
     * Delete private feed
     *
     * @return array<string, mixed>
     */
    public function deletePrivateFeed(int $id, User $user): array;
}
