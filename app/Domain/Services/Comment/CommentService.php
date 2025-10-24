<?php

declare(strict_types=1);

namespace App\Domain\Services\Comment;

use App\Domain\DTOs\Comment\CommentCreateRequestDTO;
use App\Domain\DTOs\Comment\CommentDeleteRequestDTO;
use App\Domain\DTOs\Comment\CommentUpdateRequestDTO;
use App\Models\Content\Comment;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Comment service implementation
 */
class CommentService implements CommentServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCommentsForNews(int $newsId, int $page, int $limit, string $sortBy, string $sortOrder)
    {
        try {
            $query = Comment::where('id_news', $newsId)
                ->whereNull('id_parent') // Only top-level comments
                ->with(['user', 'replies.user']);

            $comments = $query->orderBy($sortBy, $sortOrder)
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->get();

            return $comments;

        } catch (Exception $e) {
            Log::error('CommentService getCommentsForNews error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCommentReplies(int $parentId)
    {
        try {
            $replies = Comment::where('id_parent', $parentId)
                ->with(['user'])
                ->orderBy('created_at', 'asc')
                ->get();

            return $replies;

        } catch (Exception $e) {
            Log::error('CommentService getCommentReplies error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createComment(CommentCreateRequestDTO $dto, User $user)
    {
        try {
            $comment = Comment::create([
                'id_news' => $dto->newsId,
                'id_parent' => $dto->parentId,
                'id_user' => $user->id,
                'message' => $dto->message,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $comment->load('user');

        } catch (Exception $e) {
            Log::error('CommentService createComment error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateComment(CommentUpdateRequestDTO $dto, User $user)
    {
        try {
            $comment = Comment::where('id', $dto->id)
                ->where('id_user', $user->id)
                ->first();

            if (! $comment) {
                throw new Exception('Comment not found or access denied');
            }

            $comment->update([
                'message' => $dto->message,
                'updated_at' => now(),
            ]);

            return $comment->load('user');

        } catch (Exception $e) {
            Log::error('CommentService updateComment error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteComment(CommentDeleteRequestDTO $dto, User $user)
    {
        try {
            $comment = Comment::where('id', $dto->id)
                ->where('id_user', $user->id)
                ->first();

            if (! $comment) {
                throw new Exception('Comment not found or access denied');
            }

            // Delete replies first
            Comment::where('id_parent', $comment->id)->delete();

            // Delete the comment
            $comment->delete();

            return ['success' => true, 'message' => 'Comment deleted successfully'];

        } catch (Exception $e) {
            Log::error('CommentService deleteComment error: '.$e->getMessage());
            throw $e;
        }
    }
}
