<?php

declare(strict_types=1);

namespace App\Services\Influencer;

use App\DTOs\Influencer\InfluencerFeedCreateRequestDTO;
use App\DTOs\Influencer\InfluencerFeedListRequestDTO;
use App\Models\Subscription\InfluencerPage;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Log;

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
        try {
            // Get all influencer pages (public influencers that users can follow)
            $query = InfluencerPage::query();

            // Apply filters if any
            if (! empty($dto->filter)) {
                // Add filter logic here
            }

            $feeds = $query->orderBy('created_at', 'desc')
                ->limit($dto->limit)
                ->offset($dto->offset)
                ->get();

            return $feeds;

        } catch (Exception $e) {
            Log::error('InfluencerService getInfluencerFeeds error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createInfluencerFeed(InfluencerFeedCreateRequestDTO $dto, User $user)
    {
        try {
            $imagePath = null;

            // Handle image upload if provided
            if ($dto->imageFile) {
                $imagePath = $dto->imageFile->store('influencer-feeds', 'public');
            }

            $feed = InfluencerPage::create([
                'id_user' => $user->id,
                'title' => $dto->title,
                'description' => $dto->description,
                'alias' => $dto->alias,
                'page_title' => $dto->pageTitle,
                'page_description' => $dto->pageDescription,
                'image_path' => $imagePath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $feed;

        } catch (Exception $e) {
            Log::error('InfluencerService createInfluencerFeed error: '.$e->getMessage());
            throw $e;
        }
    }
}
