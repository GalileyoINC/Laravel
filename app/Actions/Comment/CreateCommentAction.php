<?php

namespace App\Actions\Comment;

use App\DTOs\Comment\CommentCreateRequestDTO;
use App\Services\Comment\CommentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateCommentAction
{
    public function __construct(
        private CommentServiceInterface $commentService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = CommentCreateRequestDTO::fromArray($data);
            if (!$dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid comment create request'],
                    'message' => 'Invalid request parameters'
                ], 400);
            }

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401
                ], 401);
            }

            $comment = $this->commentService->createComment($dto, $user);

            return response()->json($comment->toArray());

        } catch (\Exception $e) {
            Log::error('CreateCommentAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
