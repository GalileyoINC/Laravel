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
        $subscription->title = $dto->title;
        $subscription->percent = $dto->percent;
        $subscription->alias = $dto->alias;
        $subscription->description = $dto->description;
        $subscription->is_custom = (bool) $dto->isCustom;
        $subscription->show_reactions = (bool) $dto->showReactions;
        $subscription->show_comments = (bool) $dto->showComments;

        if ($dto->imageFile) {
            $imagePath = $dto->imageFile->store('subscriptions', 'public');
            $subscription->image = $imagePath;
        }

        $subscription->save();

        return $subscription;
    }
}
