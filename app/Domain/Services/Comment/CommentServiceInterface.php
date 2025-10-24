<?php

declare(strict_types=1);

namespace App\Domain\Services\Comment;

use App\Domain\DTOs\Comment\CommentCreateRequestDTO;
use App\Domain\DTOs\Comment\CommentDeleteRequestDTO;
use App\Domain\DTOs\Comment\CommentUpdateRequestDTO;
use App\Models\User\User;

/**
 * Comment service interface
 */
interface CommentServiceInterface
{
    /**
     * Get comments for news
     *
     * @return mixed
     */
    public function getCommentsForNews(int $newsId, int $page, int $limit, string $sortBy, string $sortOrder);

    /**
     * Get comment replies
     *
     * @return mixed
     */
    public function getCommentReplies(int $parentId);

    /**
     * Create comment
     *
     * @return mixed
     */
    public function createComment(CommentCreateRequestDTO $dto, User $user);

    /**
     * Update comment
     *
     * @return mixed
     */
    public function updateComment(CommentUpdateRequestDTO $dto, User $user);

    /**
     * Delete comment
     *
     * @return mixed
     */
    public function deleteComment(CommentDeleteRequestDTO $dto, User $user);
}
