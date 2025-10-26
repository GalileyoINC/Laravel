<?php

namespace App\Domain\Services\Push;

use App\Models\PushSubscription;
use App\Models\User\User;

interface PushServiceInterface
{
    /**
     * Subscribe user to push notifications
     */
    public function subscribe(User $user, array $subscriptionData): PushSubscription;

    /**
     * Unsubscribe user from push notifications
     */
    public function unsubscribe(User $user, string $endpoint): bool;

    /**
     * Send push notification to specific user
     */
    public function sendToUser(User $user, string $title, string $body, array $options = []): void;

    /**
     * Send push notification to all subscribed users
     */
    public function sendBroadcast(string $title, string $body, array $options = []): void;

    /**
     * Send push notification to specific subscription
     */
    public function sendToSubscription(PushSubscription $subscription, string $title, string $body, array $options = []): void;
}
