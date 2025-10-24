<?php

declare(strict_types=1);

namespace App\Domain\Services\Bookmark;

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
     *
     * @return array<string, mixed>
     */
    public function getBookmarks(int $page, int $limit, ?string $search, string $sortBy, string $sortOrder, ?User $user): array
    {
        try {
            if (! $user) {
                return [
                    'list' => [],
                    'count' => 0,
                    'page' => $page,
                    'page_size' => $limit,
                    'total_pages' => 0,
                ];
            }

            $query = SmsPool::with(['user', 'reactions', 'photos'])
                ->whereHas('bookmarks', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });

            // Apply search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('message', 'like', '%'.$search.'%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', '%'.$search.'%')
                                ->orWhere('last_name', 'like', '%'.$search.'%');
                        });
                });
            }

            $offset = ($page - 1) * $limit;

            $results = $query->orderBy($sortBy, $sortOrder)
                ->limit($limit)
                ->offset($offset)
                ->get();

            $totalCount = $query->count();

            // Transform each result to match frontend expectations
            $results->each(function (SmsPool $item): void {
                // Add images field
                $item->setAttribute('images', $item->photos->map(function (\App\Models\Communication\SmsPoolPhoto $photo): array {
                    return [
                        'id' => $photo->getAttribute('id'),
                        'url' => $photo->getAttribute('url'),
                        'thumbnail' => $photo->getAttribute('thumbnail_url') ?? $photo->getAttribute('url'),
                    ];
                })->toArray());

                // Add reactions
                $item->setAttribute('reactions', $item->reactions->map(function (\App\Models\Content\Reaction $reaction): array {
                    return [
                        'id' => $reaction->getAttribute('id'),
                        'type' => $reaction->getAttribute('type'),
                        'count' => $reaction->getAttribute('count'),
                        'is_user_reacted' => (bool) ($reaction->getAttribute('is_user_reacted') ?? false),
                    ];
                })->toArray());

                // Add user info
                $item->setAttribute('user_info', [
                    'id' => $item->user?->id,
                    'first_name' => $item->user?->first_name,
                    'last_name' => $item->user?->last_name,
                    'avatar' => $item->user?->avatar,
                ]);

                // Add bookmark status
                $item->setAttribute('is_bookmarked', true); // Already bookmarked since we're filtering by bookmarks

                // Add like status
                $item->setAttribute('is_liked', false);
                // TODO: Check if user has liked this post
                // $item->setAttribute('is_liked', $user->reactions()->where('post_id', $item->id)->exists());
            });

            return [
                'list' => $results->toArray(),
                'count' => $totalCount,
                'page' => $page,
                'page_size' => $limit,
                'total_pages' => ceil($totalCount / $limit),
            ];

        } catch (Exception $e) {
            Log::error('BookmarkService getBookmarks error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function createBookmark(BookmarkRequestDTO $dto, ?User $user): array
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
                'created_at' => $bookmark->created_at?->toIso8601String() ?? '',
            ];

        } catch (Exception $e) {
            Log::error('BookmarkService createBookmark error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function deleteBookmark(BookmarkRequestDTO $dto, ?User $user): array
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
