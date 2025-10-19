<?php

declare(strict_types=1);

namespace App\Domain\Actions\Comment;

use App\Domain\DTOs\Comment\CommentListRequestDTO;
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
            $dto = CommentListRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid comment list request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $commentList = $this->commentService->getCommentsForNews($dto);

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
