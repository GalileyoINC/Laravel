<?php

declare(strict_types=1);

namespace App\Domain\Services\PrivateFeed;

use App\Domain\DTOs\PrivateFeed\PrivateFeedCreateRequestDTO;
use App\Domain\DTOs\PrivateFeed\PrivateFeedListRequestDTO;
use App\Models\Subscription\FollowerList;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * PrivateFeed service implementation
 */
class PrivateFeedService implements PrivateFeedServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    public function getPrivateFeedList(PrivateFeedListRequestDTO $dto, User $user): array
    {
        try {
            $query = FollowerList::where('id_user', $user->id)
                ->with(['user', 'followers']);

            // Apply filters
            if (! empty($dto->filter)) {
                if (isset($dto->filter['title'])) {
                    $query->where('title', 'like', '%'.$dto->filter['title'].'%');
                }
            }

            $privateFeeds = $query->orderBy('created_at', 'desc')
                ->limit($dto->limit ?? 20)
                ->offset($dto->offset ?? 0)
                ->get();

            return $privateFeeds->toArray();

        } catch (Exception $e) {
            Log::error('PrivateFeedService getPrivateFeedList error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createPrivateFeed(PrivateFeedCreateRequestDTO $dto, User $user)
    {
        try {
            $followerList = new FollowerList;
            $followerList->id_user = $user->id;
            $followerList->title = $dto->title;
            $followerList->description = $dto->description;
            $followerList->created_at = now();
            $followerList->updated_at = now();

            // Handle image upload
            if ($dto->imageFile) {
                $imagePath = $dto->imageFile->store('private-feeds', 'public');
                $followerList->image_path = $imagePath ?: null;
            }

            $followerList->save();

            return $followerList;

        } catch (Exception $e) {
            Log::error('PrivateFeedService createPrivateFeed error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updatePrivateFeed(int $id, PrivateFeedCreateRequestDTO $dto, User $user)
    {
        try {
            $followerList = FollowerList::where('id', $id)
                ->where('id_user', $user->id)
                ->first();

            if (! $followerList) {
                throw new Exception('Private feed not found or unauthorized');
            }

            $followerList->title = $dto->title;
            $followerList->description = $dto->description;
            $followerList->updated_at = now();

            // Handle image upload
            if ($dto->imageFile) {
                // Delete old image if exists
                if ($followerList->image_path) {
                    Storage::disk('public')->delete($followerList->image_path);
                }

                $imagePath = $dto->imageFile->store('private-feeds', 'public');
                $followerList->image_path = $imagePath ?: null;
            }

            $followerList->save();

            return $followerList;

        } catch (Exception $e) {
            Log::error('PrivateFeedService updatePrivateFeed error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deletePrivateFeed(int $id, User $user): bool
    {
        try {
            $followerList = FollowerList::where('id', $id)
                ->where('id_user', $user->id)
                ->first();

            if (! $followerList) {
                throw new Exception('Private feed not found or unauthorized');
            }

            // Delete image if exists
            if ($followerList->image_path) {
                Storage::disk('public')->delete($followerList->image_path);
            }

            return (bool) $followerList->delete();

        } catch (Exception $e) {
            Log::error('PrivateFeedService deletePrivateFeed error: '.$e->getMessage());
            throw $e;
        }
    }
}
