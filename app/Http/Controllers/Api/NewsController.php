<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\News\CreateNewsAction;
use App\Domain\Actions\News\GetLastNewsAction;
use App\Domain\Actions\News\GetNewsByFollowerListAction;
use App\Domain\Actions\News\GetNewsByInfluencersAction;
use App\Domain\Actions\News\MuteSubscriptionAction;
use App\Domain\Actions\News\RemoveReactionAction;
use App\Domain\Actions\News\ReportNewsAction;
use App\Domain\Actions\News\SetReactionAction;
use App\Domain\Actions\Posts\DeletePostAction;
use App\Domain\Actions\Posts\GetPostAction;
use App\Domain\Actions\Posts\UpdatePostAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\PostUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

/**
 * Refactored News Controller using DDD Actions
 */
#[OA\Tag(name: 'Posts', description: 'Posts and news management endpoints')]
class NewsController extends Controller
{
    public function __construct(
        private readonly GetLastNewsAction $getLastNewsAction,
        private readonly GetNewsByInfluencersAction $getNewsByInfluencersAction,
        private readonly SetReactionAction $setReactionAction,
        private readonly RemoveReactionAction $removeReactionAction,
        private readonly ReportNewsAction $reportNewsAction,
        private readonly MuteSubscriptionAction $muteSubscriptionAction,
        private readonly GetNewsByFollowerListAction $getNewsByFollowerListAction,
        private readonly CreateNewsAction $createNewsAction,
        private readonly GetPostAction $getPostAction,
        private readonly UpdatePostAction $updatePostAction,
        private readonly DeletePostAction $deletePostAction
    ) {}

    /**
     * Get last news (POST /api/v1/news/last)
     */
    #[OA\Post(
        path: '/api/v1/news/last',
        description: 'Get latest news',
        summary: 'Get latest news',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                    new OA\Property(property: 'category', type: 'string', example: 'technology'),
                ]
            )
        ),
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Latest news retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function last(Request $request): JsonResponse
    {
        return $this->getLastNewsAction->execute($request->all());
    }

    /**
     * Get latest news (POST /api/v1/news/get-latest-news)
     */
    #[OA\Post(
        path: '/api/v1/news/get-latest-news',
        description: 'Get latest news',
        summary: 'Get latest news',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                    new OA\Property(property: 'category', type: 'string', example: 'technology'),
                ]
            )
        ),
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Latest news retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function getLatestNews(Request $request): JsonResponse
    {
        return $this->getLastNewsAction->execute($request->all());
    }

    /**
     * Get news by influencers (POST /api/v1/news/by-influencers)
     */
    #[OA\Post(
        path: '/api/v1/news/by-influencers',
        description: 'Get news from followed influencers',
        summary: 'Get news by influencers',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                    new OA\Property(property: 'influencer_ids', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2, 3]),
                ]
            )
        ),
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Influencer news retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function byInfluencers(Request $request): JsonResponse
    {
        return $this->getNewsByInfluencersAction->execute($request->all());
    }

    /**
     * Get news by subscription (POST /api/v1/news/by-subscription)
     */
    #[OA\Post(
        path: '/api/v1/news/by-subscription',
        description: 'Get news by subscription',
        summary: 'Get news by subscription',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'subscription_id', type: 'integer', example: 1),
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                ]
            )
        ),
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Subscription news retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Get news by subscription endpoint not implemented yet'),
                    ]
                )
            ),
        ]
    )]
    public function bySubscription(Request $request): JsonResponse
    {
        // Implementation for getting news by subscription
        return response()->json(['message' => 'Get news by subscription endpoint not implemented yet']);
    }

    /**
     * Set reaction on news (POST /api/v1/news/set-reaction)
     */
    #[OA\Post(
        path: '/api/v1/news/set-reaction',
        description: 'Set reaction on news post',
        summary: 'Set reaction',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['news_id', 'reaction_id'],
                properties: [
                    new OA\Property(property: 'news_id', type: 'integer', example: 1),
                    new OA\Property(property: 'reaction_id', type: 'integer', example: 1),
                ]
            )
        ),
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reaction set successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Reaction set successfully'),
                    ]
                )
            ),
        ]
    )]
    public function setReaction(Request $request): JsonResponse
    {
        return $this->setReactionAction->execute($request->all());
    }

    /**
     * Remove reaction from news (POST /api/v1/news/remove-reaction)
     */
    #[OA\Post(
        path: '/api/v1/news/remove-reaction',
        description: 'Remove reaction from news post',
        summary: 'Remove reaction',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['news_id', 'reaction_id'],
                properties: [
                    new OA\Property(property: 'news_id', type: 'integer', example: 1),
                    new OA\Property(property: 'reaction_id', type: 'integer', example: 1),
                ]
            )
        ),
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reaction removed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Reaction removed successfully'),
                    ]
                )
            ),
        ]
    )]
    public function removeReaction(Request $request): JsonResponse
    {
        return $this->removeReactionAction->execute($request->all());
    }

    /**
     * Get news by follower list (POST /api/v1/news/by-follower-list)
     */
    #[OA\Post(
        path: '/api/v1/news/by-follower-list',
        description: 'Get news by follower list',
        summary: 'Get news by follower list',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'follower_list_id', type: 'integer', example: 1),
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                ]
            )
        ),
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Follower list news retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function byFollowerList(Request $request): JsonResponse
    {
        return $this->getNewsByFollowerListAction->execute($request->all());
    }

    /**
     * Report news (POST /api/v1/news/report)
     */
    #[OA\Post(
        path: '/api/v1/news/report',
        description: 'Report inappropriate news content',
        summary: 'Report news',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['news_id', 'reason'],
                properties: [
                    new OA\Property(property: 'news_id', type: 'integer', example: 1),
                    new OA\Property(property: 'reason', type: 'string', example: 'Inappropriate content'),
                    new OA\Property(property: 'description', type: 'string', example: 'Additional details about the report'),
                ]
            )
        ),
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'News reported successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'News reported successfully'),
                    ]
                )
            ),
        ]
    )]
    public function report(Request $request): JsonResponse
    {
        return $this->reportNewsAction->execute($request->all());
    }

    /**
     * Mute subscription (POST /api/v1/news/mute)
     */
    #[OA\Post(
        path: '/api/v1/news/mute',
        description: 'Mute subscription to stop receiving news',
        summary: 'Mute subscription',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['subscription_id'],
                properties: [
                    new OA\Property(property: 'subscription_id', type: 'integer', example: 1),
                    new OA\Property(property: 'duration', type: 'integer', example: 7),
                ]
            )
        ),
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Subscription muted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Subscription muted successfully'),
                    ]
                )
            ),
        ]
    )]
    public function mute(Request $request): JsonResponse
    {
        return $this->muteSubscriptionAction->execute($request->all());
    }

    /**
     * Create news (POST /api/v1/news/create)
     */
    #[OA\Post(
        path: '/api/v1/news/create',
        description: 'Create new news post',
        summary: 'Create news',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['content'],
                properties: [
                    new OA\Property(property: 'content', type: 'string', example: 'This is my new news post content'),
                    new OA\Property(property: 'images', type: 'array', items: new OA\Items(type: 'string'), example: ['https://example.com/image1.jpg']),
                    new OA\Property(property: 'tags', type: 'array', items: new OA\Items(type: 'string'), example: ['news', 'update']),
                    new OA\Property(property: 'category', type: 'string', example: 'technology'),
                ]
            )
        ),
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'News created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'News created successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function create(Request $request): JsonResponse
    {
        return $this->createNewsAction->execute($request->all());
    }

    // ===== POSTS CRUD METHODS =====

    /**
     * Get a specific post (GET /api/v1/posts/{id})
     */
    #[OA\Get(
        path: '/api/v1/posts/{id}',
        summary: 'Get a specific post',
        description: 'Retrieve details of a specific post by ID',
        tags: ['Posts'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Post ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Post retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'content', type: 'string', example: 'This is a sample post content'),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                new OA\Property(
                                    property: 'images',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'url', type: 'string', example: 'https://example.com/image.jpg'),
                                            new OA\Property(property: 'thumbnail', type: 'string', example: 'https://example.com/thumb.jpg'),
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: 'reactions',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'type', type: 'string', example: 'like'),
                                            new OA\Property(property: 'count', type: 'integer', example: 5),
                                            new OA\Property(property: 'is_user_reacted', type: 'boolean', example: false),
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: 'user_info',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'first_name', type: 'string', example: 'John'),
                                        new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
                                        new OA\Property(property: 'avatar', type: 'string', example: 'https://example.com/avatar.jpg'),
                                    ]
                                ),
                                new OA\Property(property: 'is_bookmarked', type: 'boolean', example: false),
                                new OA\Property(property: 'is_liked', type: 'boolean', example: false),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Post not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Post not found'),
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
    public function getPost(int $id): JsonResponse
    {
        return $this->getPostAction->execute(['id' => $id]);
    }

    /**
     * Update a post (PUT /api/v1/posts/{id})
     */
    #[OA\Put(
        path: '/api/v1/posts/{id}',
        summary: 'Update a post',
        description: 'Update an existing post by ID',
        tags: ['Posts'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Post ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['content'],
                properties: [
                    new OA\Property(property: 'content', type: 'string', example: 'Updated post content'),
                    new OA\Property(property: 'images', type: 'array', items: new OA\Items(type: 'string'), example: ['https://example.com/image1.jpg', 'https://example.com/image2.jpg']),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Post updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Post updated successfully'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'content', type: 'string', example: 'Updated post content'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Post not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Post not found'),
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
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function updatePost(PostUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $data['id'] = $id;

        return $this->updatePostAction->execute($data);
    }

    /**
     * Delete a post (DELETE /api/v1/posts/{id})
     */
    #[OA\Delete(
        path: '/api/v1/posts/{id}',
        summary: 'Delete a post',
        description: 'Delete a post by ID',
        tags: ['Posts'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Post ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Post deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Post deleted successfully'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Post not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Post not found'),
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
    public function deletePost(int $id): JsonResponse
    {
        return $this->deletePostAction->execute(['id' => $id]);
    }
}
