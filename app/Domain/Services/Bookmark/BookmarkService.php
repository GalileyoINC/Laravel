<?php

declare(strict_types=1);

namespace App\Domain\Services\Bookmark;

use App\Domain\DTOs\Bookmark\BookmarkListRequestDTO;
use App\Domain\DTOs\Bookmark\BookmarkRequestDTO;
use App\Models\Communication\SmsPool;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Bookmark service implementation
 */
class BookmarkService implements BookmarkServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBookmarks(BookmarkListRequestDTO $dto, ?User $user)
    {
        try {
            if (! $user) {
                return [
                    'list' => [],
                    'count' => 0,
                    'page' => $dto->page,
                    'page_size' => $dto->pageSize,
                    'total_pages' => 0,
                ];
            }

            $query = SmsPool::with(['user', 'reactions', 'photos'])
                ->whereHas('bookmarks', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });

            // Apply type filter
            if ($dto->type) {
                $query->where('purpose', $dto->type);
            }

            $offset = ($dto->page - 1) * $dto->pageSize;

            $results = $query->orderBy('created_at', 'desc')
                ->limit($dto->pageSize)
                ->offset($offset)
                ->get();

            $totalCount = $query->count();

            // Transform each result to match frontend expectations
            $results->each(function ($item) use ($user) {
                // Add images field
                $item->images = $item->photos->map(fn ($photo) => [
                    'id' => $photo->id,
                    'url' => $photo->url,
                    'thumbnail' => $photo->thumbnail_url ?? $photo->url,
                ])->toArray();

                // Add reactions
                $item->reactions = $item->reactions->map(fn ($reaction) => [
                    'id' => $reaction->id,
                    'type' => $reaction->type,
                    'count' => $reaction->count,
                    'is_user_reacted' => $reaction->is_user_reacted ?? false,
                ])->toArray();

                // Add user info
                $item->user_info = [
                    'id' => $item->user->id,
                    'first_name' => $item->user->first_name,
                    'last_name' => $item->user->last_name,
                    'avatar' => $item->user->avatar,
                ];

                // Add bookmark status
                $item->is_bookmarked = true; // Already bookmarked since we're filtering by bookmarks

                // Add like status
                $item->is_liked = false;
                if ($user) {
                    // TODO: Check if user has liked this post
                    // $item->is_liked = $user->reactions()->where('post_id', $item->id)->exists();
                }
            });

            return [
                'list' => $results->toArray(),
                'count' => $totalCount,
                'page' => $dto->page,
                'page_size' => $dto->pageSize,
                'total_pages' => ceil($totalCount / $dto->pageSize),
            ];

        } catch (Exception $e) {
            Log::error('BookmarkService getBookmarks error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createBookmark(BookmarkRequestDTO $dto, ?User $user)
    {
        try {
            if (! $user) {
                throw new Exception('User not authenticated');
            }

            // Check if post exists
            $post = SmsPool::find($dto->postId);
            if (! $post) {
                throw new Exception('Post not found');
            }

            // Check if already bookmarked
            $existingBookmark = $user->bookmarks()->where('post_id', $dto->postId)->first();
            if ($existingBookmark) {
                throw new Exception('Post already bookmarked');
            }

            // Create bookmark
            $bookmark = $user->bookmarks()->create([
                'post_id' => $dto->postId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return [
                'id' => $bookmark->id,
                'post_id' => $dto->postId,
                'created_at' => $bookmark->created_at->toIso8601String(),
            ];

        } catch (Exception $e) {
            Log::error('BookmarkService createBookmark error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteBookmark(BookmarkRequestDTO $dto, ?User $user)
    {
        try {
            if (! $user) {
                throw new Exception('User not authenticated');
            }

            // Find and delete bookmark
            $bookmark = $user->bookmarks()->where('post_id', $dto->postId)->first();
            if (! $bookmark) {
                throw new Exception('Bookmark not found');
            }

            $bookmark->delete();

            return [
                'post_id' => $dto->postId,
                'deleted' => true,
            ];

        } catch (Exception $e) {
            Log::error('BookmarkService deleteBookmark error: '.$e->getMessage());
            throw $e;
        }
    }
}
