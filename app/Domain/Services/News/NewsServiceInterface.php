<?php

declare(strict_types=1);

namespace App\Domain\Services\News;

use App\Domain\DTOs\News\GetNewsByFollowerListRequestDTO;
use App\Domain\DTOs\News\MuteSubscriptionRequestDTO;
use App\Domain\DTOs\News\NewsBySubscriptionDTO;
use App\Domain\DTOs\News\NewsListRequestDTO;
use App\Domain\DTOs\News\ReactionRequestDTO;
use App\Domain\DTOs\News\ReportNewsRequestDTO;
use App\Models\User\User;

/**
 * News service interface
 */
interface NewsServiceInterface
{
    /**
     * Get last news
     *
     * @return mixed
     */
    public function getLastNews(NewsListRequestDTO $dto, ?User $user);

    /**
     * Get news by influencers
     *
     * @return mixed
     */
    public function getNewsByInfluencers(NewsListRequestDTO $dto, ?User $user);

    /**
     * Get news by subscription
     *
     * @return mixed
     */
    public function getNewsBySubscription(NewsBySubscriptionDTO $dto, ?User $user);

    /**
     * Set reaction on news
     *
     * @return mixed
     */
    public function setReaction(ReactionRequestDTO $dto, User $user);

    /**
     * Remove reaction from news
     *
     * @return mixed
     */
    public function removeReaction(ReactionRequestDTO $dto, User $user);

    /**
     * Get news by follower list
     *
     * @return mixed
     */
    public function getNewsByFollowerList(GetNewsByFollowerListRequestDTO $dto, User $user);

    /**
     * Report news
     *
     * @return mixed
     */
    public function reportNews(ReportNewsRequestDTO $dto, User $user);

    /**
     * Mute subscription
     *
     * @return mixed
     */
    public function muteSubscription(MuteSubscriptionRequestDTO $dto, User $user);

    /**
     * Unmute subscription
     *
     * @return mixed
     */
    public function unmuteSubscription(MuteSubscriptionRequestDTO $dto, User $user);
}
