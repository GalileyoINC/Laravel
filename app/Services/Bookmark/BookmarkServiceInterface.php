<?php

namespace App\Services\Bookmark;

use App\DTOs\Bookmark\BookmarkRequestDTO;
use App\DTOs\Bookmark\BookmarkListRequestDTO;
use App\Models\User;

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
