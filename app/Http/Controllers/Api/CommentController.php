<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Comment\GetCommentsAction;
use App\Actions\Comment\CreateCommentAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Refactored Comment Controller using DDD Actions
 * Handles comment operations: get, get replies, create, update, delete
 */
class CommentController extends Controller
{
    public function __construct(
        private GetCommentsAction $getCommentsAction,
        private CreateCommentAction $createCommentAction
    ) {}

    /**
     * Get comments for news (POST /api/v1/comment/get)
     */
    public function get(Request $request): JsonResponse
    {
        return $this->getCommentsAction->execute($request->all());
    }

    /**
     * Get comment replies (POST /api/v1/comment/get-replies)
     */
    public function getReplies(Request $request): JsonResponse
    {
        // Implementation for getting comment replies
        return response()->json(['message' => 'Get replies endpoint not implemented yet']);
    }

    /**
     * Create comment (POST /api/v1/comment/create)
     */
    public function create(Request $request): JsonResponse
    {
        return $this->createCommentAction->execute($request->all());
    }

    /**
     * Update comment (POST /api/v1/comment/update)
     */
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