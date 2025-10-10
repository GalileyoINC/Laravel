<?php

namespace App\Services\Comment;

use App\DTOs\Comment\CommentListRequestDTO;
use App\DTOs\Comment\CommentCreateRequestDTO;
use App\DTOs\Comment\CommentUpdateRequestDTO;
use App\DTOs\Comment\CommentDeleteRequestDTO;
use App\Models\User;

/**
 * Comment service interface
 */
interface CommentServiceInterface
{
    /**
     * Get comments for news
     *
     * @param CommentListRequestDTO $dto
     * @return mixed
     */
    public function getCommentsForNews(CommentListRequestDTO $dto);

    /**
     * Get comment replies
     *
     * @param int $parentId
     * @return mixed
     */
    public function getCommentReplies(int $parentId);

    /**
     * Create comment
     *
     * @param CommentCreateRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function createComment(CommentCreateRequestDTO $dto, User $user);

    /**
     * Update comment
     *
     * @param CommentUpdateRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function updateComment(CommentUpdateRequestDTO $dto, User $user);

    /**
     * Delete comment
     *
     * @param CommentDeleteRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function deleteComment(CommentDeleteRequestDTO $dto, User $user);
}
