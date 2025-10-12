<?php

declare(strict_types=1);

namespace App\Services\Subscription;

use App\DTOs\Subscription\FeedOptionsDTO;
use App\DTOs\Subscription\MarketstackSubscriptionDTO;
use App\DTOs\Subscription\SubscriptionRequestDTO;
use App\Models\Subscription\FollowerList;
use App\Models\Subscription\Subscription;
use App\Models\User\User\User;
use App\Models\User\UserSubscription;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Subscription service implementation
 */
class SubscriptionService implements SubscriptionServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function setSubscription(SubscriptionRequestDTO $dto, User $user): array
    {
        try {
            $subscription = Subscription::find($dto->idSubscription);
            if (! $subscription) {
                throw new Exception('Subscription not found');
            }

            if ($dto->checked) {
                // Subscribe user
                UserSubscription::updateOrCreate(
                    [
                        'id_user' => $user->id,
                        'id_subscription' => $dto->idSubscription,
                        'sub_type' => $dto->subType,
                    ],
                    [
                        'zip' => $dto->zip,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            } else {
                // Unsubscribe user
                UserSubscription::where('id_user', $user->id)
                    ->where('id_subscription', $dto->idSubscription)
                    ->where('sub_type', $dto->subType)
                    ->delete();
            }

            return [
                'success' => true,
                'message' => $dto->checked ? 'Subscribed successfully' : 'Unsubscribed successfully',
                'subscription_id' => $dto->idSubscription,
                'checked' => $dto->checked,
            ];

        } catch (Exception $e) {
            Log::error('SubscriptionService setSubscription error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFeedList(FeedOptionsDTO $dto, ?User $user): array
    {
        try {
            $query = Subscription::where('is_active', true)
                ->where('is_public', true);

            if ($dto->category) {
                $query->where('category', $dto->category);
            }

            $subscriptions = $query->orderBy('name')
                ->limit($dto->limit)
                ->offset($dto->offset)
                ->get();

            // Add user subscription status if user is authenticated
            if ($user) {
                $userSubscriptions = UserSubscription::where('id_user', $user->id)
                    ->pluck('id_subscription')
                    ->toArray();

                $subscriptions->each(function ($subscription) use ($userSubscriptions) {
                    $subscription->is_subscribed = in_array($subscription->id, $userSubscriptions);
                });
            }

            return $subscriptions->toArray();

        } catch (Exception $e) {
            Log::error('SubscriptionService getFeedList error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFeedCategories(): array
    {
        try {
            $categories = Subscription::where('is_active', true)
                ->where('is_public', true)
                ->distinct()
                ->pluck('category')
                ->filter()
                ->values()
                ->toArray();

            return [
                'categories' => $categories,
                'total' => count($categories),
            ];

        } catch (Exception $e) {
            Log::error('SubscriptionService getFeedCategories error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSatelliteFeedList(FeedOptionsDTO $dto, ?User $user): array
    {
        try {
            $query = Subscription::where('is_active', true)
                ->where('is_public', true)
                ->where('type', 'satellite');

            if ($dto->category) {
                $query->where('category', $dto->category);
            }

            $subscriptions = $query->orderBy('name')
                ->limit($dto->limit)
                ->offset($dto->offset)
                ->get();

            // Add user subscription status if user is authenticated
            if ($user) {
                $userSubscriptions = UserSubscription::where('id_user', $user->id)
                    ->where('sub_type', 'satellite')
                    ->pluck('id_subscription')
                    ->toArray();

                $subscriptions->each(function ($subscription) use ($userSubscriptions) {
                    $subscription->is_subscribed = in_array($subscription->id, $userSubscriptions);
                });
            }

            return $subscriptions->toArray();

        } catch (Exception $e) {
            Log::error('SubscriptionService getSatelliteFeedList error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addMarketstackSubscription(MarketstackSubscriptionDTO $dto, User $user): array
    {
        try {
            // Create custom subscription for marketstack data
            $subscription = Subscription::create([
                'name' => $dto->name ?? $dto->symbol,
                'description' => $dto->description ?? "Marketstack {$dto->type} subscription for {$dto->symbol}",
                'type' => 'marketstack',
                'sub_type' => $dto->type,
                'symbol' => $dto->symbol,
                'is_active' => true,
                'is_public' => false, // Custom subscriptions are private
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return [
                'success' => true,
                'message' => 'Marketstack subscription created successfully',
                'subscription' => $subscription->toArray(),
            ];

        } catch (Exception $e) {
            Log::error('SubscriptionService addMarketstackSubscription error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFeedOptions(): array
    {
        try {
            return [
                'max_subscriptions' => 50,
                'allowed_types' => ['regular', 'satellite', 'marketstack'],
                'categories' => $this->getFeedCategories()['categories'],
                'features' => [
                    'zip_code_targeting' => true,
                    'satellite_feeds' => true,
                    'custom_marketstack' => true,
                ],
            ];

        } catch (Exception $e) {
            Log::error('SubscriptionService getFeedOptions error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deletePrivateFeed(int $id, User $user): array
    {
        try {
            $followerList = FollowerList::where('id', $id)
                ->where('id_user', $user->id)
                ->first();

            if (! $followerList) {
                throw new Exception('Private feed not found or unauthorized');
            }

            $followerList->delete();

            return [
                'success' => true,
                'message' => 'Private feed deleted successfully',
            ];

        } catch (Exception $e) {
            Log::error('SubscriptionService deletePrivateFeed error: '.$e->getMessage());
            throw $e;
        }
    }
}
