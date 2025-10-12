<?php

declare(strict_types=1);

namespace App\Services\News;

use App\DTOs\News\GetNewsByFollowerListRequestDTO;
use App\DTOs\News\MuteSubscriptionRequestDTO;
use App\DTOs\News\NewsBySubscriptionDTO;
use App\DTOs\News\NewsListRequestDTO;
use App\DTOs\News\ReactionRequestDTO;
use App\DTOs\News\ReportNewsRequestDTO;
use App\Models\Communication\SmsPool;
use App\Models\Content\Reaction;
use App\Models\Content\Report;
use App\Models\User\User\Mute;
use App\Models\User\User\User;
use Exception;
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
            $query = SmsPool::with(['user', 'reactions', 'photos']);

            // Apply filters
            if ($dto->type) {
                $query->where('purpose', $dto->type);
            }

            if ($dto->search) {
                $query->where(function ($q) use ($dto) {
                    $q->where('body', 'like', '%'.$dto->search.'%')
                        ->orWhere('short_body', 'like', '%'.$dto->search.'%');
                });
            }

            $news = $query->orderBy('created_at', 'desc')
                ->limit($dto->limit)
                ->offset($dto->offset)
                ->get();

            // Transform each news item to match frontend expectations
            $news->each(function ($item) {
                // Add images field
                $item->images = $item->photos->map(function ($photo) {
                    $sizes = $photo->sizes ? json_decode((string) $photo->sizes, true) : [];

                    // Transform sizes to match frontend expectations
                    $transformedSizes = [];
                    foreach ($sizes as $type => $sizeData) {
                        $transformedSizes[] = [
                            'type' => $type,
                            'url' => $photo->folder_name ? $photo->folder_name.'/'.$sizeData['name'] : $sizeData['name'],
                            'width' => $sizeData['width'] ?? 0,
                            'height' => $sizeData['height'] ?? 0,
                            'name' => $sizeData['name'] ?? '',
                        ];
                    }

                    return [
                        'id' => $photo->id,
                        'sizes' => $transformedSizes,
                    ];
                })->toArray();

                // Add required fields for frontend
                $item->type = $this->getFeedItemType($item->purpose);
                $item->title = $item->short_body ?? '';
                $item->subtitle = '';
                $item->body ??= '';
                $item->image = null; // Will be populated from images if available
                $item->emergency_level = null;
                $item->location = null;
                $item->is_liked = false;
                $item->is_bookmarked = false;
                $item->is_subscribed = false;
                $item->is_owner = false;
                $item->comment_quantity = 0;

                // Add financial-specific fields for financial items (only if subscription is financial)
                if ($item->type === 'financial' && $item->id_subscription) {
                    // Parse financial data from SMS body like YII does
                    preg_match('/\: (.*) \(.*, (.*)\)$/', (string) $item->body, $matches);

                    if (! empty($matches[1])) {
                        $number = str_replace(',', '', $matches[1]);
                        $afterDot = mb_strlen(explode('.', $number)[1] ?? '');

                        $item->percent = $matches[2];
                        $item->price = number_format($number, $afterDot);
                    } else {
                        // No financial data found, don't add fake data
                        $item->percent = null;
                        $item->price = null;
                    }
                }

                // Transform reactions to match frontend expectations (like YII)
                $reactionsGrouped = [];
                foreach ($item->reactions as $reaction) {
                    $reactionId = $reaction->id;
                    if (! isset($reactionsGrouped[$reactionId])) {
                        $reactionsGrouped[$reactionId] = [
                            'id' => (string) $reactionId,
                            'cnt' => 0,
                            'selected' => false, // Will be determined by user's reactions
                        ];
                    }
                    $reactionsGrouped[$reactionId]['cnt']++;
                }
                $item->reactions = array_values($reactionsGrouped);
            });

            return $news;

        } catch (Exception $e) {
            Log::error('NewsService getLastNews error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getNewsByInfluencers(NewsListRequestDTO $dto, ?User $user)
    {
        try {
            $query = SmsPool::with(['user', 'reactions', 'photos'])
                ->where('purpose', 2); // Influencer purpose

            // Apply filters
            if ($dto->search) {
                $query->where(function ($q) use ($dto) {
                    $q->where('body', 'like', '%'.$dto->search.'%')
                        ->orWhere('short_body', 'like', '%'.$dto->search.'%');
                });
            }

            $news = $query->orderBy('created_at', 'desc')
                ->limit($dto->limit)
                ->offset($dto->offset)
                ->get();

            // Transform each news item to match frontend expectations
            $news->each(function ($item) {
                // Add images field
                $item->images = $item->photos->map(function ($photo) {
                    $sizes = $photo->sizes ? json_decode((string) $photo->sizes, true) : [];

                    // Transform sizes to match frontend expectations
                    $transformedSizes = [];
                    foreach ($sizes as $type => $sizeData) {
                        $transformedSizes[] = [
                            'type' => $type,
                            'url' => $photo->folder_name ? $photo->folder_name.'/'.$sizeData['name'] : $sizeData['name'],
                            'width' => $sizeData['width'] ?? 0,
                            'height' => $sizeData['height'] ?? 0,
                            'name' => $sizeData['name'] ?? '',
                        ];
                    }

                    return [
                        'id' => $photo->id,
                        'sizes' => $transformedSizes,
                    ];
                })->toArray();

                // Add required fields for frontend
                $item->type = $this->getFeedItemType($item->purpose);
                $item->title = $item->short_body ?? '';
                $item->subtitle = '';
                $item->body ??= '';
                $item->image = null; // Will be populated from images if available
                $item->emergency_level = null;
                $item->location = null;
                $item->is_liked = false;
                $item->is_bookmarked = false;
                $item->is_subscribed = false;
                $item->is_owner = false;
                $item->comment_quantity = 0;

                // Add financial-specific fields for financial items (only if subscription is financial)
                if ($item->type === 'financial' && $item->id_subscription) {
                    // Parse financial data from SMS body like YII does
                    preg_match('/\: (.*) \(.*, (.*)\)$/', (string) $item->body, $matches);

                    if (! empty($matches[1])) {
                        $number = str_replace(',', '', $matches[1]);
                        $afterDot = mb_strlen(explode('.', $number)[1] ?? '');

                        $item->percent = $matches[2];
                        $item->price = number_format($number, $afterDot);
                    } else {
                        // No financial data found, don't add fake data
                        $item->percent = null;
                        $item->price = null;
                    }
                }

                // Transform reactions to match frontend expectations (like YII)
                $reactionsGrouped = [];
                foreach ($item->reactions as $reaction) {
                    $reactionId = $reaction->id;
                    if (! isset($reactionsGrouped[$reactionId])) {
                        $reactionsGrouped[$reactionId] = [
                            'id' => (string) $reactionId,
                            'cnt' => 0,
                            'selected' => false, // Will be determined by user's reactions
                        ];
                    }
                    $reactionsGrouped[$reactionId]['cnt']++;
                }
                $item->reactions = array_values($reactionsGrouped);
            });

            return $news;

        } catch (Exception $e) {
            Log::error('NewsService getNewsByInfluencers error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getNewsBySubscription(NewsBySubscriptionDTO $dto, ?User $user)
    {
        try {
            $query = SmsPool::whereHas('user', function ($q) {
                $q->where('is_influencer', true);
            })->with(['user', 'reactions']);

            // Apply filters
            if ($dto->type) {
                $query->where('purpose', $dto->type);
            }

            if ($dto->search) {
                $query->where(function ($q) use ($dto) {
                    $q->where('body', 'like', '%'.$dto->search.'%')
                        ->orWhere('short_body', 'like', '%'.$dto->search.'%');
                });
            }

            $news = $query->orderBy('created_at', 'desc')
                ->limit($dto->limit)
                ->offset($dto->offset)
                ->get();

            return $news;

        } catch (Exception $e) {
            Log::error('NewsService getNewsByInfluencers error: '.$e->getMessage());
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
                    'updated_at' => now(),
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
                'updated_at' => now(),
            ]);

            return $reaction;

        } catch (Exception $e) {
            Log::error('NewsService setReaction error: '.$e->getMessage());
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

        } catch (Exception $e) {
            Log::error('NewsService removeReaction error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Get news by follower list
     */
    public function getNewsByFollowerList(GetNewsByFollowerListRequestDTO $dto, User $user)
    {
        try {
            $query = SmsPool::whereHas('user.followerLists', function ($q) use ($dto) {
                $q->where('id_follower_list', $dto->id_follower_list);
            })->with(['user', 'reactions']);

            $news = $query->orderBy('created_at', 'desc')
                ->limit($dto->page_size)
                ->offset(($dto->page - 1) * $dto->page_size)
                ->get();

            return $news;

        } catch (Exception $e) {
            Log::error('NewsService getNewsByFollowerList error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Report news
     */
    public function reportNews(ReportNewsRequestDTO $dto, User $user)
    {
        try {
            $report = Report::create([
                'id_news' => $dto->id_news,
                'id_user' => $user->id,
                'reason' => $dto->reason,
                'additional_text' => $dto->additional_text,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return ['success' => true, 'message' => 'News reported successfully', 'report_id' => $report->id];

        } catch (Exception $e) {
            Log::error('NewsService reportNews error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Mute subscription
     */
    public function muteSubscription(MuteSubscriptionRequestDTO $dto, User $user)
    {
        try {
            $mute = Mute::updateOrCreate(
                [
                    'id_subscription' => $dto->id_subscription,
                    'id_user' => $user->id,
                ],
                [
                    'is_muted' => true,
                    'updated_at' => now(),
                ]
            );

            return ['success' => true, 'message' => 'Subscription muted successfully'];

        } catch (Exception $e) {
            Log::error('NewsService muteSubscription error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Unmute subscription
     */
    public function unmuteSubscription(MuteSubscriptionRequestDTO $dto, User $user)
    {
        try {
            $mute = Mute::where('id_subscription', $dto->id_subscription)
                ->where('id_user', $user->id)
                ->first();

            if ($mute) {
                $mute->update(['is_muted' => false, 'updated_at' => now()]);

                return ['success' => true, 'message' => 'Subscription unmuted successfully'];
            }

            return ['success' => false, 'message' => 'Subscription not found'];

        } catch (Exception $e) {
            Log::error('NewsService unmuteSubscription error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Get feed item type based on purpose
     */
    private function getFeedItemType(int $purpose): string
    {
        return match ($purpose) {
            1 => 'general',  // Changed from 'financial' to 'general'
            2 => 'influencer',
            3 => 'subscription',
            4 => 'general',
            default => 'general'
        };
    }
}
