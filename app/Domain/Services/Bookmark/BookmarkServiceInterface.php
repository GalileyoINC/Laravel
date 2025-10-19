<?php

declare(strict_types=1);

namespace App\Domain\Services\Bookmark;

use App\Domain\DTOs\Bookmark\BookmarkListRequestDTO;
use App\Domain\DTOs\Bookmark\BookmarkRequestDTO;
use App\Models\User\User;

interface BookmarkServiceInterface
{
    /**
     * Get bookmarks list
     *
     * @return array<string, mixed>
     */
    public function getBookmarks(BookmarkListRequestDTO $dto, ?User $user): array;

    /**
     * Create bookmark
     *
     * @return array<string, mixed>
     */
    public function createBookmark(BookmarkRequestDTO $dto, ?User $user): array;

    /**
     * Delete bookmark
     *
     * @return array<string, mixed>
     */
    public function deleteBookmark(BookmarkRequestDTO $dto, ?User $user): array;
}
