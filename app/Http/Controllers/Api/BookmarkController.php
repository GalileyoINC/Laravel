<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Bookmark\CreateBookmarkAction;
use App\Domain\Actions\Bookmark\DeleteBookmarkAction;
use App\Domain\Actions\Bookmark\GetBookmarksAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bookmark\BookmarkListRequest;
use App\Http\Requests\Bookmark\BookmarkRequest;
use App\Http\Resources\ErrorResource;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Bookmark controller with DDD structure
 */
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
    public function delete(BookmarkRequest $request): JsonResponse
    {
        // Request validation is handled automatically by BookmarkRequest
        $result = $this->deleteBookmarkAction->execute($request->validated());

        // Return the result directly since DeleteBookmarkAction already returns JsonResponse
        return $result;
    }
}
