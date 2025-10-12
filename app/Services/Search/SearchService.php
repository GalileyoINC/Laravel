<?php

declare(strict_types=1);

namespace App\Services\Search;

use App\DTOs\Search\SearchRequestDTO;
use App\Models\Communication\SmsPool;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Search service implementation
 */
class SearchService implements SearchServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function search(SearchRequestDTO $dto, ?User $user)
    {
        try {
            $query = SmsPool::with(['user', 'reactions', 'photos']);

            // Apply search phrase
            if ($dto->phrase) {
                $query->where(function ($q) use ($dto) {
                    $q->where('body', 'like', '%'.$dto->phrase.'%')
                        ->orWhere('short_body', 'like', '%'.$dto->phrase.'%')
                        ->orWhere('title', 'like', '%'.$dto->phrase.'%');
                });
            }

            // Apply type filter
            if ($dto->type) {
                $query->where('purpose', $dto->type);
            }

            // Apply additional filters
            if (! empty($dto->filters)) {
                foreach ($dto->filters as $key => $value) {
                    if ($value !== null && $value !== '') {
                        $query->where($key, $value);
                    }
                }
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
                $item->is_bookmarked = false;
                if ($user) {
                    // TODO: Check if user has bookmarked this post
                    // $item->is_bookmarked = $user->bookmarks()->where('post_id', $item->id)->exists();
                }

                // Add like status
                $item->is_liked = false;
                if ($user) {
                    // TODO: Check if user has liked this post
                    // $item->is_liked = $user->reactions()->where('post_id', $item->id)->exists();
                }
            });

            return [
                'results' => $results->toArray(),
                'count' => $totalCount,
                'page' => $dto->page,
                'page_size' => $dto->pageSize,
                'total_pages' => ceil($totalCount / $dto->pageSize),
            ];

        } catch (Exception $e) {
            Log::error('SearchService error: '.$e->getMessage());
            throw $e;
        }
    }
}
