<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\News\GetLastNewsAction;
use App\Actions\News\SetReactionAction;
use App\Actions\News\RemoveReactionAction;
use App\Actions\News\ReportNewsAction;
use App\Actions\News\MuteSubscriptionAction;
use App\Actions\News\UnmuteSubscriptionAction;
use App\Actions\News\GetNewsByFollowerListAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Refactored News Controller using DDD Actions
 */
class NewsController extends Controller
{
    public function __construct(
        private GetLastNewsAction $getLastNewsAction,
        private SetReactionAction $setReactionAction,
        private RemoveReactionAction $removeReactionAction,
        private ReportNewsAction $reportNewsAction,
        private MuteSubscriptionAction $muteSubscriptionAction,
        private UnmuteSubscriptionAction $unmuteSubscriptionAction,
        private GetNewsByFollowerListAction $getNewsByFollowerListAction
    ) {}

    /**
     * Get last news (POST /api/v1/news/last)
     */
    public function last(Request $request): JsonResponse
    {
        return $this->getLastNewsAction->execute($request->all());
    }

    /**
     * Get news by influencers (POST /api/v1/news/by-influencers)
     */
    public function byInfluencers(Request $request): JsonResponse
    {
        // Implementation for getting news by influencers
        return response()->json(['message' => 'Get news by influencers endpoint not implemented yet']);
    }

    /**
     * Get news by subscription (POST /api/v1/news/by-subscription)
     */
    public function bySubscription(Request $request): JsonResponse
    {
        // Implementation for getting news by subscription
        return response()->json(['message' => 'Get news by subscription endpoint not implemented yet']);
    }

    /**
     * Set reaction on news (POST /api/v1/news/set-reaction)
     */
    public function setReaction(Request $request): JsonResponse
    {
        return $this->setReactionAction->execute($request->all());
    }

    /**
     * Remove reaction from news (POST /api/v1/news/remove-reaction)
     */
    public function removeReaction(Request $request): JsonResponse
    {
        return $this->removeReactionAction->execute($request->all());
    }

    /**
     * Get news by follower list (POST /api/v1/news/by-follower-list)
     */
    public function byFollowerList(Request $request): JsonResponse
    {
        return $this->getNewsByFollowerListAction->execute($request->all());
    }

    /**
     * Report news (POST /api/v1/news/report)
     */
    public function report(Request $request): JsonResponse
    {
        return $this->reportNewsAction->execute($request->all());
    }

    /**
     * Mute subscription (POST /api/v1/news/mute)
     */
    public function mute(Request $request): JsonResponse
    {
        return $this->muteSubscriptionAction->execute($request->all());
    }

    /**
     * Unmute subscription (POST /api/v1/news/unmute)
     */
    public function unmute(Request $request): JsonResponse
    {
        return $this->unmuteSubscriptionAction->execute($request->all());
    }
}