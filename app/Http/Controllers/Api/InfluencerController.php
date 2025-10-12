<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Influencer\CreateInfluencerFeedAction;
use App\Actions\Influencer\GetInfluencerFeedListAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Refactored Influencer Controller using DDD Actions
 * Handles influencer network management: feeds, content publishing
 */
class InfluencerController extends Controller
{
    public function __construct(
        private readonly GetInfluencerFeedListAction $getInfluencerFeedListAction,
        private readonly CreateInfluencerFeedAction $createInfluencerFeedAction
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
