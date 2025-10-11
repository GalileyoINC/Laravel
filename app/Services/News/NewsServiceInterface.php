<?php

namespace App\Services\News;

use App\DTOs\News\NewsListRequestDTO;
use App\DTOs\News\NewsBySubscriptionDTO;
use App\DTOs\News\ReactionRequestDTO;
use App\DTOs\News\ReportNewsRequestDTO;
use App\DTOs\News\MuteSubscriptionRequestDTO;
use App\DTOs\News\GetNewsByFollowerListRequestDTO;
use App\Models\User;

/**
 * News service interface
 */
interface NewsServiceInterface
{
    /**
     * Get last news
     *
     * @param NewsListRequestDTO $dto
     * @param User|null $user
     * @return mixed
     */
    public function getLastNews(NewsListRequestDTO $dto, ?User $user);

    /**
     * Get news by influencers
     *
     * @param NewsListRequestDTO $dto
     * @param User|null $user
     * @return mixed
     */
    public function getNewsByInfluencers(NewsListRequestDTO $dto, ?User $user);

    /**
     * Get news by subscription
     *
     * @param NewsBySubscriptionDTO $dto
     * @param User|null $user
     * @return mixed
     */
    public function getNewsBySubscription(NewsBySubscriptionDTO $dto, ?User $user);

    /**
     * Set reaction on news
     *
     * @param ReactionRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function setReaction(ReactionRequestDTO $dto, User $user);

    /**
     * Remove reaction from news
     *
     * @param ReactionRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function removeReaction(ReactionRequestDTO $dto, User $user);

    /**
     * Get news by follower list
     *
     * @param GetNewsByFollowerListRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function getNewsByFollowerList(GetNewsByFollowerListRequestDTO $dto, User $user);

    /**
     * Report news
     *
     * @param ReportNewsRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function reportNews(ReportNewsRequestDTO $dto, User $user);

    /**
     * Mute subscription
     *
     * @param MuteSubscriptionRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function muteSubscription(MuteSubscriptionRequestDTO $dto, User $user);

    /**
     * Unmute subscription
     *
     * @param MuteSubscriptionRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function unmuteSubscription(MuteSubscriptionRequestDTO $dto, User $user);
}
