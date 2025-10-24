<?php

declare(strict_types=1);

namespace App\Domain\Actions\Posts;

use App\Domain\DTOs\Posts\PostCreateRequestDTO;
use App\Domain\DTOs\Posts\PostListRequestDTO;
use App\Domain\DTOs\Posts\PostUpdateRequestDTO;
use App\Domain\Services\Posts\PostsServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Get posts list action
 */
class GetPostsListAction
{
    public function __construct(
        private readonly PostsServiceInterface $postsService
    ) {}

    /**
     * Execute get posts list action
     *
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        try {
            $dto = PostListRequestDTO::fromArray($data);
            $result = $this->postsService->getPostsList($dto);

            return response()->json([
                'status' => 'success',
                'data' => $result['data'],
                'pagination' => $result['pagination'],
            ]);

        } catch (Exception $e) {
            Log::error('GetPostsListAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'Failed to get posts list',
                'code' => 500,
            ], 500);
        }
    }
}

/**
 * Create post action
 */
class CreatePostAction
{
    public function __construct(
        private readonly PostsServiceInterface $postsService
    ) {}

    /**
     * Execute create post action
     *
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        try {
            $dto = PostCreateRequestDTO::fromArray($data);
            $result = $this->postsService->createPost($dto);

            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            Log::error('CreatePostAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'Failed to create post',
                'code' => 500,
            ], 500);
        }
    }
}

/**
 * Update post action
 */
class UpdatePostAction
{
    public function __construct(
        private readonly PostsServiceInterface $postsService
    ) {}

    /**
     * Execute update post action
     *
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        try {
            $dto = PostUpdateRequestDTO::fromArray($data);
            $result = $this->postsService->updatePost($dto);

            return response()->json([
                'status' => 'success',
                'message' => 'Post updated successfully',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            Log::error('UpdatePostAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'Failed to update post',
                'code' => 500,
            ], 500);
        }
    }
}

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
            $this->postsService->deletePost($postId);

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
