<?php

declare(strict_types=1);

namespace App\Services\Comment;

use App\DTOs\Comment\CommentCreateRequestDTO;
use App\DTOs\Comment\CommentDeleteRequestDTO;
use App\DTOs\Comment\CommentListRequestDTO;
use App\DTOs\Comment\CommentUpdateRequestDTO;
use App\Models\User\User\User;

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
    public function getCommentsForNews(CommentListRequestDTO $dto);

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
