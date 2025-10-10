<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\News\GetLastNewsAction;
use App\Actions\News\SetReactionAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Refactored News Controller using DDD Actions
 */
class NewsController extends Controller
{
    public function __construct(
        private GetLastNewsAction $getLastNewsAction,
        private SetReactionAction $setReactionAction
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
        // Implementation for removing reaction
        return response()->json(['message' => 'Remove reaction endpoint not implemented yet']);
    }
}