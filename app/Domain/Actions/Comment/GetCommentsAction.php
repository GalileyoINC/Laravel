<?php

declare(strict_types=1);

namespace App\Domain\Actions\Comment;

use App\Domain\Services\Comment\CommentServiceInterface;
use Illuminate\Http\JsonResponse;

class GetCommentsAction
{
    public function __construct(
        private readonly CommentServiceInterface $commentService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $newsId = $data['news_id'] ?? 0;
        $page = $data['page'] ?? 1;
        $limit = $data['limit'] ?? 20;
        $sortBy = $data['sort_by'] ?? 'created_at';
        $sortOrder = $data['sort_order'] ?? 'desc';

        $commentList = $this->commentService->getCommentsForNews($newsId, $page, $limit, $sortBy, $sortOrder);

        return response()->json($commentList->toArray());
    }
}
