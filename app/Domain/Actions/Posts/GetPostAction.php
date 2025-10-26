<?php

declare(strict_types=1);

namespace App\Domain\Actions\Posts;

use App\Domain\Services\Posts\PostsServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * Get single post action
 */
class GetPostAction
{
    public function __construct(
        private readonly PostsServiceInterface $postsService
    ) {}

    /**
     * Execute get post action
     *
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $postId = $data['id'] ?? 0;
        $result = $this->postsService->getPost($postId);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
