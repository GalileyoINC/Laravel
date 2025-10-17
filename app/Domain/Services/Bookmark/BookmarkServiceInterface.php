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
     */
    public function getBookmarks(BookmarkListRequestDTO $dto, ?User $user);

    /**
     * Create bookmark
     */
    public function createBookmark(BookmarkRequestDTO $dto, ?User $user);

    /**
     * Delete bookmark
     */
    public function deleteBookmark(BookmarkRequestDTO $dto, ?User $user);
}
