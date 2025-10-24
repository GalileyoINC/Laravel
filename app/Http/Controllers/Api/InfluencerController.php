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
use OpenApi\Attributes as OA;

/**
 * Refactored Influencer Controller using DDD Actions
 * Handles influencer network management: feeds, content publishing
 */
#[OA\Tag(name: 'Influencers', description: 'Influencer management endpoints')]
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
    #[OA\Get(
        path: '/api/v1/influencer/list',
        summary: 'List all influencers',
        description: 'Get a list of all influencer users in the system',
        tags: ['Influencers'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'page',
                description: 'Page number for pagination',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', minimum: 1, example: 1)
            ),
            new OA\Parameter(
                name: 'per_page',
                description: 'Number of items per page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, example: 15)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of influencers retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'email', type: 'string', example: 'influencer@example.com'),
                                    new OA\Property(property: 'first_name', type: 'string', example: 'John'),
                                    new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
                                    new OA\Property(property: 'is_influencer', type: 'boolean', example: true),
                                    new OA\Property(property: 'influencer_verified_at', type: 'string', format: 'date-time'),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                    new OA\Property(property: 'about', type: 'string', example: 'Travel blogger'),
                                    new OA\Property(property: 'avatar', type: 'string', example: 'https://example.com/avatar.jpg'),
                                    new OA\Property(property: 'header_image', type: 'string', example: 'https://example.com/header.jpg'),
                                ]
                            )
                        ),
                        new OA\Property(property: 'count', type: 'integer', example: 25),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
        ]
    )]
    public function listInfluencers(InfluencersListRequest $request): JsonResponse
    {
        return $this->getInfluencersListAction->execute($request->validated());
    }
}
