<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Bookmark\CreateBookmarkAction;
use App\Domain\Actions\Bookmark\DeleteBookmarkAction;
use App\Domain\Actions\Bookmark\GetBookmarksAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bookmark\BookmarkListRequest;
use App\Http\Requests\Bookmark\BookmarkRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

/**
 * Bookmark controller with DDD structure
 */
#[OA\Tag(name: 'Bookmarks', description: 'Bookmark management endpoints')]
class BookmarkController extends Controller
{
    public function __construct(
        private readonly GetBookmarksAction $getBookmarksAction,
        private readonly CreateBookmarkAction $createBookmarkAction,
        private readonly DeleteBookmarkAction $deleteBookmarkAction
    ) {}

    /**
     * Get bookmarks list
     *
     * GET /api/v1/bookmark/list
     */
    #[OA\Get(
        path: '/api/v1/bookmark/list',
        description: 'Get paginated list of user bookmarks',
        summary: 'List bookmarks',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', description: 'Page number', schema: new OA\Schema(type: 'integer', example: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Items per page', schema: new OA\Schema(type: 'integer', example: 15)),
            new OA\Parameter(name: 'type', in: 'query', description: 'Bookmark type filter', schema: new OA\Schema(type: 'string', example: 'news')),
        ],
        tags: ['Bookmarks'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Bookmarks list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'meta', properties: [
                            new OA\Property(property: 'current_page', type: 'integer', example: 1),
                            new OA\Property(property: 'last_page', type: 'integer', example: 5),
                            new OA\Property(property: 'per_page', type: 'integer', example: 15),
                            new OA\Property(property: 'total', type: 'integer', example: 75),
                        ], type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'User not authenticated'),
                    ]
                )
            ),
        ]
    )]
    public function list(BookmarkListRequest $request): JsonResponse
    {
        // Request validation is handled automatically by BookmarkListRequest
        $result = $this->getBookmarksAction->execute($request->validated());

        // Return the result directly since GetBookmarksAction already returns JsonResponse
        return $result;
    }

    /**
     * Get bookmarks list (legacy endpoint)
     *
     * POST /api/bookmark/index
     */
    public function index(BookmarkListRequest $request): JsonResponse
    {
        return $this->list($request);
    }

    /**
     * Create bookmark
     *
     * POST /api/bookmark/create
     */
    #[OA\Post(
        path: '/api/v1/bookmark/create',
        description: 'Create a new bookmark',
        summary: 'Create bookmark',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['item_id', 'item_type'],
                properties: [
                    new OA\Property(property: 'item_id', type: 'integer', example: 123, description: 'ID of the item to bookmark'),
                    new OA\Property(property: 'item_type', type: 'string', example: 'news', enum: ['news', 'post', 'comment']),
                    new OA\Property(property: 'note', type: 'string', example: 'Important article'),
                ]
            )
        ),
        tags: ['Bookmarks'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Bookmark created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Bookmark created successfully'),
                        new OA\Property(property: 'data', type: 'object'),
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
    public function create(BookmarkRequest $request): JsonResponse
    {
        // Request validation is handled automatically by BookmarkRequest
        $result = $this->createBookmarkAction->execute($request->validated());

        // Return the result directly since CreateBookmarkAction already returns JsonResponse
        return $result;
    }

    /**
     * Delete bookmark
     *
     * DELETE /api/bookmark/delete
     */
    #[OA\Delete(
        path: '/api/v1/bookmark/delete',
        description: 'Delete a bookmark',
        summary: 'Delete bookmark',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['bookmark_id'],
                properties: [
                    new OA\Property(property: 'bookmark_id', type: 'integer', example: 123, description: 'ID of the bookmark to delete'),
                ]
            )
        ),
        tags: ['Bookmarks'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Bookmark deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Bookmark deleted successfully'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Bookmark not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Bookmark not found'),
                    ]
                )
            ),
        ]
    )]
    public function delete(BookmarkRequest $request): JsonResponse
    {
        // Request validation is handled automatically by BookmarkRequest
        $result = $this->deleteBookmarkAction->execute($request->validated());

        // Return the result directly since DeleteBookmarkAction already returns JsonResponse
        return $result;
    }
}
