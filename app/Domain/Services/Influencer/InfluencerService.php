<?php

declare(strict_types=1);

namespace App\Domain\Services\Influencer;

use App\Domain\DTOs\Influencer\InfluencerFeedCreateRequestDTO;
use App\Domain\DTOs\Influencer\InfluencerFeedListRequestDTO;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Influencer service implementation
 */
class InfluencerService implements InfluencerServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getInfluencerFeeds(InfluencerFeedListRequestDTO $dto, User $user)
    {
        // Implementation for getInfluencerFeeds
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function createInfluencerFeed(InfluencerFeedCreateRequestDTO $dto, User $user)
    {
        // Implementation for createInfluencerFeed
        return [];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return Collection<int, User>
     */
    public function getInfluencers(array $data): Collection
    {
        return User::where('is_influencer', true)
            ->where('status', User::STATUS_ACTIVE)
            ->select([
                'id',
                'email',
                'first_name',
                'last_name',
                'is_influencer',
                'influencer_verified_at',
                'created_at',
                'about',
                'avatar',
                'header_image',
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
