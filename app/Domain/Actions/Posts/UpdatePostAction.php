<?php

declare(strict_types=1);

namespace App\Domain\Actions\Posts;

use App\Domain\DTOs\Posts\PostUpdateRequestDTO;
use App\Domain\Services\Posts\PostsServiceInterface;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
        $dto = PostUpdateRequestDTO::fromArray($data);
        $user = Auth::user();

        if (! $user instanceof User) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $result = $this->postsService->updatePost($dto, $user);

        return response()->json([
            'status' => 'success',
            'message' => 'Post updated successfully',
            'data' => $result,
        ]);
    }
}
