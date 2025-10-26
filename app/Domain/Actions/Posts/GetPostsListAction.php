<?php

declare(strict_types=1);

namespace App\Domain\Actions\Posts;

use App\Domain\Services\Posts\PostsServiceInterface;
use Illuminate\Http\JsonResponse;

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
        $perPage = $data['per_page'] ?? 15;
        $page = $data['page'] ?? 1;
        $userId = $data['user_id'] ?? null;

        $result = $this->postsService->getPostsList($perPage, $page, $userId);

        return response()->json([
            'status' => 'success',
            'data' => $result['data'],
            'pagination' => $result['pagination'],
        ]);
    }
}
