<?php

declare(strict_types=1);

namespace App\Services\Influencer;

use App\DTOs\Influencer\InfluencerFeedCreateRequestDTO;
use App\DTOs\Influencer\InfluencerFeedListRequestDTO;
use App\Models\User\User;

/**
 * Influencer service interface
 */
interface InfluencerServiceInterface
{
    /**
     * Get influencer feeds
     *
     * @return mixed
     */
    public function getInfluencerFeeds(InfluencerFeedListRequestDTO $dto, User $user);

    /**
     * Create influencer feed
     *
     * @return mixed
     */
    public function createInfluencerFeed(InfluencerFeedCreateRequestDTO $dto, User $user);
}
