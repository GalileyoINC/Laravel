<?php

declare(strict_types=1);

namespace App\Domain\Services\Influencer;

use App\Domain\DTOs\Influencer\InfluencerFeedCreateRequestDTO;
use App\Domain\DTOs\Influencer\InfluencerFeedListRequestDTO;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * Get influencers list
     *
     * @param  array<string, mixed>  $data
     * @return Collection<int, User>
     */
    public function getInfluencers(array $data): Collection;
}
