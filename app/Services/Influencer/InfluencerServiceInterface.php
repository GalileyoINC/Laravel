<?php

namespace App\Services\Influencer;

use App\DTOs\Influencer\InfluencerFeedListRequestDTO;
use App\DTOs\Influencer\InfluencerFeedCreateRequestDTO;
use App\Models\User;

/**
 * Influencer service interface
 */
interface InfluencerServiceInterface
{
    /**
     * Get influencer feeds
     *
     * @param InfluencerFeedListRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function getInfluencerFeeds(InfluencerFeedListRequestDTO $dto, User $user);

    /**
     * Create influencer feed
     *
     * @param InfluencerFeedCreateRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function createInfluencerFeed(InfluencerFeedCreateRequestDTO $dto, User $user);
}
