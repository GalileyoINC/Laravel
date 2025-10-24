<?php

declare(strict_types=1);

namespace App\Domain\Services\Posts;

use App\Domain\DTOs\Posts\PostCreateRequestDTO;
use App\Domain\DTOs\Posts\PostUpdateRequestDTO;
use App\Models\Communication\SmsPool;
use App\Models\User\User;

/**
 * Posts service interface
 */
interface PostsServiceInterface
{
    /**
     * Get posts list
     *
     * @return array<string, mixed>
     */
    public function getPostsList(int $perPage, int $page, ?int $userId): array;

    /**
     * Get single post
     *
     * @return array<string, mixed>
     */
    public function getPost(int $id): array;

    /**
     * Create a new post
     */
    public function createPost(PostCreateRequestDTO $dto, User $user): SmsPool;

    /**
     * Update an existing post
     */
    public function updatePost(PostUpdateRequestDTO $dto, User $user): SmsPool;

    /**
     * Delete a post
     */
    public function deletePost(int $id, User $user): void;
}
