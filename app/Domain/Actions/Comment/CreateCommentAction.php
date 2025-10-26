<?php

declare(strict_types=1);

namespace App\Domain\Actions\Comment;

use App\Domain\DTOs\Comment\CommentCreateRequestDTO;
use App\Domain\Services\Comment\CommentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CreateCommentAction
{
    public function __construct(
        private readonly CommentServiceInterface $commentService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $dto = CommentCreateRequestDTO::fromArray($data);
        if (! $dto->validate()) {
            return response()->json([
                'errors' => ['Invalid comment create request'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $comment = $this->commentService->createComment($dto, $user);

        return response()->json($comment->toArray());
    }
}
