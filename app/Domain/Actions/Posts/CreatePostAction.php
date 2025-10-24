<?php

declare(strict_types=1);

namespace App\Domain\Actions\Posts;

use App\Domain\DTOs\Posts\PostCreateRequestDTO;
use App\Domain\Services\Posts\PostsServiceInterface;
use App\Models\User\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            $user = Auth::user();
            
            if (!$user instanceof User) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $result = $this->postsService->createPost($dto, $user);

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
