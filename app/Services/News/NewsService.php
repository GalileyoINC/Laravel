<?php

namespace App\Services\News;

use App\DTOs\News\NewsListRequestDTO;
use App\DTOs\News\NewsBySubscriptionDTO;
use App\DTOs\News\ReactionRequestDTO;
use App\Models\User;
use App\Models\News;
use App\Models\Reaction;
use Illuminate\Support\Facades\Log;

/**
 * News service implementation
 */
class NewsService implements NewsServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLastNews(NewsListRequestDTO $dto, ?User $user)
    {
        try {
            $query = News::with(['user', 'reactions']);

            // Apply filters
            if ($dto->type) {
                $query->where('type', $dto->type);
            }

            if ($dto->search) {
                $query->where(function ($q) use ($dto) {
                    $q->where('title', 'like', '%' . $dto->search . '%')
                      ->orWhere('content', 'like', '%' . $dto->search . '%');
                });
            }

            $news = $query->orderBy('created_at', 'desc')
                ->limit($dto->limit)
                ->offset($dto->offset)
                ->get();

            return $news;

        } catch (\Exception $e) {
            Log::error('NewsService getLastNews error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getNewsByInfluencers(NewsListRequestDTO $dto, ?User $user)
    {
        try {
            $query = News::whereHas('user', function ($q) {
                $q->where('is_influencer', true);
            })->with(['user', 'reactions']);

            // Apply filters
            if ($dto->type) {
                $query->where('type', $dto->type);
            }

            if ($dto->search) {
                $query->where(function ($q) use ($dto) {
                    $q->where('title', 'like', '%' . $dto->search . '%')
                      ->orWhere('content', 'like', '%' . $dto->search . '%');
                });
            }

            $news = $query->orderBy('created_at', 'desc')
                ->limit($dto->limit)
                ->offset($dto->offset)
                ->get();

            return $news;

        } catch (\Exception $e) {
            Log::error('NewsService getNewsByInfluencers error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getNewsBySubscription(NewsBySubscriptionDTO $dto, ?User $user)
    {
        try {
            $query = News::where('id_subscription', $dto->idSubscription)
                ->with(['user', 'reactions']);

            $news = $query->orderBy('created_at', 'desc')
                ->limit($dto->limit)
                ->offset($dto->offset)
                ->get();

            return $news;

        } catch (\Exception $e) {
            Log::error('NewsService getNewsBySubscription error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setReaction(ReactionRequestDTO $dto, User $user)
    {
        try {
            // Check if reaction already exists
            $existingReaction = Reaction::where('id_news', $dto->idNews)
                ->where('id_user', $user->id)
                ->first();

            if ($existingReaction) {
                // Update existing reaction
                $existingReaction->update([
                    'reaction_type' => $dto->reactionType,
                    'message' => $dto->message,
                    'updated_at' => now()
                ]);
                return $existingReaction;
            }

            // Create new reaction
            $reaction = Reaction::create([
                'id_news' => $dto->idNews,
                'id_user' => $user->id,
                'reaction_type' => $dto->reactionType,
                'message' => $dto->message,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return $reaction;

        } catch (\Exception $e) {
            Log::error('NewsService setReaction error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeReaction(ReactionRequestDTO $dto, User $user)
    {
        try {
            $reaction = Reaction::where('id_news', $dto->idNews)
                ->where('id_user', $user->id)
                ->first();

            if ($reaction) {
                $reaction->delete();
                return ['success' => true, 'message' => 'Reaction removed successfully'];
            }

            return ['success' => false, 'message' => 'Reaction not found'];

        } catch (\Exception $e) {
            Log::error('NewsService removeReaction error: ' . $e->getMessage());
            throw $e;
        }
    }
}
