<?php

declare(strict_types=1);

namespace App\Domain\Actions\Posts;

use App\Domain\Services\Posts\PostsServiceInterface;
use App\Models\User\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Delete post action
 */
class DeletePostAction
{
    public function __construct(
        private readonly PostsServiceInterface $postsService
    ) {}

    /**
     * Execute delete post action
     *
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        try {
            $postId = $data['id'] ?? 0;
            $user = Auth::user();
            
            if (!$user instanceof User) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $this->postsService->deletePost($postId, $user);

            return response()->json([
                'status' => 'success',
                'message' => 'Post deleted successfully',
            ]);

        } catch (Exception $e) {
            Log::error('DeletePostAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'Failed to delete post',
                'code' => 500,
            ], 500);
        }
    }
}
