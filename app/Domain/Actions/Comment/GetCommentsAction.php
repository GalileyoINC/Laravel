<?php

declare(strict_types=1);

namespace App\Domain\Actions\Comment;

use App\Domain\Services\Comment\CommentServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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
        try {
            $newsId = $data['news_id'] ?? 0;
            $page = $data['page'] ?? 1;
            $limit = $data['limit'] ?? 20;
            $sortBy = $data['sort_by'] ?? 'created_at';
            $sortOrder = $data['sort_order'] ?? 'desc';

            $commentList = $this->commentService->getCommentsForNews($newsId, $page, $limit, $sortBy, $sortOrder);

            return response()->json($commentList->toArray());

        } catch (Exception $e) {
            Log::error('GetCommentsAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
