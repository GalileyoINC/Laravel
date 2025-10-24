<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Comment\CreateCommentAction;
use App\Domain\Actions\Comment\GetCommentsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Comments', description: 'Comment management operations')]
class CommentController extends Controller
{
    public function __construct(
        private readonly GetCommentsAction $getCommentsAction,
        private readonly CreateCommentAction $createCommentAction
    ) {}

    /**
     * Get comments for news (POST /api/v1/comment/get)
     */
    #[OA\Post(
        path: '/api/v1/comment/get',
        description: 'Get comments for a specific news item',
        summary: 'Get comments',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['news_id'],
                properties: [
                    new OA\Property(property: 'news_id', type: 'integer', example: 1),
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                ]
            )
        ),
        tags: ['Comments'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Comments retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Invalid news_id'),
                    ]
                )
            ),
        ]
    )]
    public function get(Request $request): JsonResponse
    {
        return $this->getCommentsAction->execute($request->all());
    }

    /**
     * Get comment replies (POST /api/v1/comment/get-replies)
     */
    #[OA\Post(
        path: '/api/v1/comment/get-replies',
        description: 'Get replies for a specific comment',
        summary: 'Get comment replies',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['comment_id'],
                properties: [
                    new OA\Property(property: 'comment_id', type: 'integer', example: 1),
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                ]
            )
        ),
        tags: ['Comments'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Comment replies retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Get replies endpoint not implemented yet'),
                    ]
                )
            ),
        ]
    )]
    public function getReplies(Request $request): JsonResponse
    {
        // Implementation for getting comment replies
        return response()->json(['message' => 'Get replies endpoint not implemented yet']);
    }

    /**
     * Create comment (POST /api/v1/comment/create)
     */
    #[OA\Post(
        path: '/api/v1/comment/create',
        description: 'Create a new comment',
        summary: 'Create comment',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['news_id', 'content'],
                properties: [
                    new OA\Property(property: 'news_id', type: 'integer', example: 1),
                    new OA\Property(property: 'content', type: 'string', example: 'This is a great article!'),
                    new OA\Property(property: 'parent_id', type: 'integer', example: null, description: 'Parent comment ID for replies'),
                ]
            )
        ),
        tags: ['Comments'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Comment created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Unauthorized'),
                    ]
                )
            ),
        ]
    )]
    public function create(Request $request): JsonResponse
    {
        return $this->createCommentAction->execute($request->all());
    }

    /**
     * Update comment (POST /api/v1/comment/update)
     */
    #[OA\Post(
        path: '/api/v1/comment/update',
        description: 'Update an existing comment',
        summary: 'Update comment',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['comment_id', 'content'],
                properties: [
                    new OA\Property(property: 'comment_id', type: 'integer', example: 1),
                    new OA\Property(property: 'content', type: 'string', example: 'Updated comment content'),
                ]
            )
        ),
        tags: ['Comments'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Comment updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Update comment endpoint not implemented yet'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Unauthorized'),
                    ]
                )
            ),
        ]
    )]
    public function update(Request $request): JsonResponse
    {
        // Implementation for updating comment
        return response()->json(['message' => 'Update comment endpoint not implemented yet']);
    }

    /**
     * Delete comment (POST /api/v1/comment/delete)
     */
    public function delete(Request $request): JsonResponse
    {
        // Implementation for deleting comment
        return response()->json(['message' => 'Delete comment endpoint not implemented yet']);
    }
}
