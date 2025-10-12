<?php

declare(strict_types=1);

namespace App\Modules\System\Infrastructure\Http\Controllers;

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
     * POST /api/bookmark/index
     */
    public function index(BookmarkListRequest $request): JsonResponse
    {
        try {
            // Request validation is handled automatically by BookmarkListRequest
            $result = $this->getBookmarksAction->execute($request->validated());

            // Return the result directly since GetBookmarksAction already returns JsonResponse
            return $result;

        } catch (Exception $e) {
            // Use ErrorResource for consistent error format
            return response()->json(new ErrorResource([
                'message' => $e->getMessage(),
                'code' => 500,
                'trace_id' => uniqid(),
            ]), 500);
        }
    }

    /**
     * Create bookmark
     *
     * POST /api/bookmark/create
     */
    public function create(BookmarkRequest $request): JsonResponse
    {
        try {
            // Request validation is handled automatically by BookmarkRequest
            $result = $this->createBookmarkAction->execute($request->validated());

            // Return the result directly since CreateBookmarkAction already returns JsonResponse
            return $result;

        } catch (Exception $e) {
            // Use ErrorResource for consistent error format
            return response()->json(new ErrorResource([
                'message' => $e->getMessage(),
                'code' => 500,
                'trace_id' => uniqid(),
            ]), 500);
        }
    }

    /**
     * Delete bookmark
     *
     * DELETE /api/bookmark/delete
     */
    public function delete(BookmarkRequest $request): JsonResponse
    {
        try {
            // Request validation is handled automatically by BookmarkRequest
            $result = $this->deleteBookmarkAction->execute($request->validated());

            // Return the result directly since DeleteBookmarkAction already returns JsonResponse
            return $result;

        } catch (Exception $e) {
            // Use ErrorResource for consistent error format
            return response()->json(new ErrorResource([
                'message' => $e->getMessage(),
                'code' => 500,
                'trace_id' => uniqid(),
            ]), 500);
        }
    }
}
