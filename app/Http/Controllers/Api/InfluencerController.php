<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Influencer\GetInfluencerFeedListAction;
use App\Actions\Influencer\CreateInfluencerFeedAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Refactored Influencer Controller using DDD Actions
 * Handles influencer network management: feeds, content publishing
 */
class InfluencerController extends Controller
{
    public function __construct(
        private GetInfluencerFeedListAction $getInfluencerFeedListAction,
        private CreateInfluencerFeedAction $createInfluencerFeedAction
    ) {}

    /**
     * Get influencer feed list (POST /api/v1/influencer/index)
     */
    public function index(Request $request): JsonResponse
    {
        return $this->getInfluencerFeedListAction->execute($request->all());
    }

    /**
     * Create influencer feed (POST /api/v1/influencer/create)
     */
    public function create(Request $request): JsonResponse
    {
        return $this->createInfluencerFeedAction->execute($request->all());
    }
}