<?php

declare(strict_types=1);

namespace App\Domain\Actions\Subscription;

use App\Domain\DTOs\Subscription\SubscriptionStoreRequestDTO;
use App\Models\Subscription\Subscription;

final class CreateSubscriptionAction
{
    public function execute(SubscriptionStoreRequestDTO $dto): Subscription
    {
        $subscription = new Subscription();
        $subscription->id_subscription_category = $dto->categoryId;
        $subscription->name = $dto->title; // Use name instead of title
        $subscription->title = $dto->title;
        $subscription->percent = $dto->percent !== null ? (float) $dto->percent : 0.0;
        $subscription->description = $dto->description;
        $subscription->is_custom = (bool) $dto->isCustom;
        $subscription->show_reactions = (bool) $dto->showReactions;
        $subscription->show_comments = (bool) $dto->showComments;

        if ($dto->imageFile) {
            $imagePath = $dto->imageFile->store('subscriptions', 'public');
            // Store image path in a custom field or handle differently
            // $subscription->image = $imagePath; // This property doesn't exist
        }

        $subscription->save();

        return $subscription;
    }
}
