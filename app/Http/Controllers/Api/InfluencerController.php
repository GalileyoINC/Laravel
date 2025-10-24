<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Influencer\CreateInfluencerFeedAction;
use App\Domain\Actions\Influencer\GetInfluencerFeedListAction;
use App\Domain\Actions\Influencer\GetInfluencersListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Influencer\InfluencersListRequest;
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
        private readonly CreateInfluencerFeedAction $createInfluencerFeedAction,
        private readonly GetInfluencersListAction $getInfluencersListAction
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

    /**
     * List all influencer users (GET /api/v1/influencer/list)
     */
    public function listInfluencers(InfluencersListRequest $request): JsonResponse
    {
        return $this->getInfluencersListAction->execute($request->validated());
    }
}
