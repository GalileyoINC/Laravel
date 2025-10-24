<?php

declare(strict_types=1);

namespace App\Domain\Services\Posts;

use App\Domain\DTOs\Posts\PostCreateRequestDTO;
use App\Domain\DTOs\Posts\PostUpdateRequestDTO;
use App\Models\Communication\SmsPool;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Posts service implementation
 */
class PostsService implements PostsServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    public function getPostsList(int $perPage, int $page, ?int $userId): array
    {
        try {
            $query = SmsPool::with(['user:id,first_name,last_name,email'])
                ->where('purpose', 1); // Posts purpose

            // Apply user filter if specified
            if ($userId) {
                $query->where('id_user', $userId);
            }

            $posts = $query->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return [
                'data' => $posts->items(),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                ],
            ];

        } catch (Exception $e) {
            Log::error('PostsService getPostsList error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    public function getPost(int $id): array
    {
        try {
            $post = SmsPool::with(['user', 'reactions', 'photos'])
                ->where('purpose', 1) // Posts purpose
                ->findOrFail($id);

            // Transform post data to include images, reactions, and user info
            $post->setAttribute('images', $post->photos->map(function (\App\Models\Communication\SmsPoolPhoto $photo): array {
                return [
                    'id' => $photo->getAttribute('id'),
                    'url' => $photo->getAttribute('url'),
                    'thumbnail' => $photo->getAttribute('thumbnail_url') ?? $photo->getAttribute('url'),
                ];
            })->toArray());

            $post->setAttribute('reactions', $post->reactions->map(function (\App\Models\Content\Reaction $reaction): array {
                return [
                    'id' => $reaction->getAttribute('id'),
                    'type' => $reaction->getAttribute('type'),
                    'count' => $reaction->getAttribute('count'),
                    'is_user_reacted' => (bool) ($reaction->getAttribute('is_user_reacted') ?? false),
                ];
            })->toArray());

            $post->setAttribute('user_info', [
                'id' => $post->user?->id,
                'first_name' => $post->user?->first_name,
                'last_name' => $post->user?->last_name,
                'avatar' => $post->user?->avatar,
            ]);

            // Add bookmark status
            $user = Auth::user();
            $post->setAttribute('is_bookmarked', $user ? $user->bookmarks()->where('post_id', $post->id)->exists() : false);

            // Add like status
            $post->setAttribute('is_liked', $user ? $user->reactions()->where('post_id', $post->id)->exists() : false);

            return $post->toArray();

        } catch (Exception $e) {
            Log::error('PostsService getPost error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    public function createPost(PostCreateRequestDTO $dto, User $user): SmsPool
    {
        try {
            DB::beginTransaction();

            // Create the post
            $post = SmsPool::create([
                'id_user' => $dto->userId ?? $user->id,
                'purpose' => 1, // Posts purpose
                'body' => $dto->content,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Handle media files
            foreach ($dto->media as $mediaFile) {
                if ($mediaFile instanceof \Illuminate\Http\UploadedFile) {
                    $path = $mediaFile->store('posts-media', 'public');
                    $filename = $mediaFile->getClientOriginalName();

                    DB::table('sms_pool_photo')->insert([
                        'id_sms_pool' => $post->id,
                        'folder_name' => 'posts-media',
                        'web_name' => $filename,
                        'sizes' => json_encode([
                            [
                                'url' => $path,
                                'width' => null,
                                'height' => null,
                                'size' => 'original',
                            ],
                        ]),
                        'created_at' => now(),
                        'updated_at' => now(),
                        'uuid' => \Illuminate\Support\Str::uuid(),
                    ]);
                }
            }

            DB::commit();

            return $post;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('PostsService createPost error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updatePost(PostUpdateRequestDTO $dto, User $user): SmsPool
    {
        try {
            $post = SmsPool::where('id', $dto->id)
                ->where('purpose', 1)
                ->first();

            if (! $post) {
                throw new Exception('Post not found');
            }

            // Check if user owns the post or is admin
            if ($post->id_user !== $user->id && $user->role !== 1) {
                throw new Exception('Unauthorized');
            }

            $post->body = $dto->content;
            $post->updated_at = now();
            $post->save();

            return $post;

        } catch (Exception $e) {
            Log::error('PostsService updatePost error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deletePost(int $id, User $user): void
    {
        try {
            $post = SmsPool::where('id', $id)
                ->where('purpose', 1)
                ->first();

            if (! $post) {
                throw new Exception('Post not found');
            }

            // Check if user owns the post or is admin
            if ($post->id_user !== $user->id && $user->role !== 1) {
                throw new Exception('Unauthorized');
            }

            DB::beginTransaction();

            // Delete associated media files
            DB::table('sms_pool_photo')
                ->where('id_sms_pool', $id)
                ->delete();

            // Delete the post
            $post->delete();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('PostsService deletePost error: '.$e->getMessage());
            throw $e;
        }
    }
}
