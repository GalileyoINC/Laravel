<?php

namespace App\Domain\Services\Push;

use App\Models\PushSubscription;
use App\Models\User\User;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription as WebPushSubscription;

class PushService implements PushServiceInterface
{
    private WebPush $webPush;

    public function __construct()
    {
        $this->webPush = new WebPush([
            'VAPID' => [
                'subject' => config('services.push.vapid_email'),
                'publicKey' => config('services.push.vapid_public_key'),
                'privateKey' => config('services.push.vapid_private_key'),
            ],
        ]);
    }

    public function subscribe(User $user, array $subscriptionData): PushSubscription
    {
        // Check if subscription already exists
        $existing = PushSubscription::where('endpoint', $subscriptionData['endpoint'])
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            // Update existing subscription
            $existing->update([
                'public_key' => $subscriptionData['keys']['p256dh'],
                'auth_token' => $subscriptionData['keys']['auth'],
                'content_encoding' => $subscriptionData['contentEncoding'] ?? 'aesgcm',
                'is_active' => true,
            ]);

            return $existing;
        }

        // Create new subscription
        return PushSubscription::create([
            'user_id' => $user->id,
            'endpoint' => $subscriptionData['endpoint'],
            'public_key' => $subscriptionData['keys']['p256dh'],
            'auth_token' => $subscriptionData['keys']['auth'],
            'content_encoding' => $subscriptionData['contentEncoding'] ?? 'aesgcm',
            'is_active' => true,
        ]);
    }

    public function unsubscribe(User $user, string $endpoint): bool
    {
        $subscription = PushSubscription::where('user_id', $user->id)
            ->where('endpoint', $endpoint)
            ->first();

        if ($subscription) {
            $subscription->update(['is_active' => false]);
            return true;
        }

        return false;
    }

    public function sendToUser(User $user, string $title, string $body, array $options = []): void
    {
        $subscriptions = PushSubscription::where('user_id', $user->id)
            ->where('is_active', true)
            ->get();

        foreach ($subscriptions as $subscription) {
            $this->sendToSubscription($subscription, $title, $body, $options);
        }
    }

    public function sendBroadcast(string $title, string $body, array $options = []): void
    {
        $subscriptions = PushSubscription::where('is_active', true)->get();

        foreach ($subscriptions as $subscription) {
            $this->sendToSubscription($subscription, $title, $body, $options);
        }
    }

    public function sendToSubscription(PushSubscription $subscription, string $title, string $body, array $options = []): void
    {
        try {
            $webPushSubscription = new WebPushSubscription(
                $subscription->endpoint,
                $subscription->public_key,
                $subscription->auth_token
            );

            $payload = json_encode(array_merge([
                'title' => $title,
                'body' => $body,
                'icon' => $options['icon'] ?? '/galileyo_new_logo.png',
            ], $options));

            $result = $this->webPush->sendOneNotification($webPushSubscription, $payload);

            if ($result->isSuccess()) {
                Log::info('Push notification sent successfully', [
                    'subscription_id' => $subscription->id,
                    'user_id' => $subscription->user_id,
                ]);
            } else {
                Log::warning('Failed to send push notification', [
                    'subscription_id' => $subscription->id,
                    'reason' => $result->getReason(),
                ]);

                // Deactivate subscription if expired
                if ($result->isSubscriptionExpired()) {
                    $subscription->update(['is_active' => false]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error sending push notification', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
