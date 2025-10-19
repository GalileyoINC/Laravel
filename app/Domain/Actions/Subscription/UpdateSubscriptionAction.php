<?php

declare(strict_types=1);

namespace App\Domain\Actions\Subscription;

use App\Domain\DTOs\Subscription\SubscriptionUpdateRequestDTO;
use App\Models\Subscription\Subscription;
use Illuminate\Support\Facades\Storage;

final class UpdateSubscriptionAction
{
    public function execute(SubscriptionUpdateRequestDTO $dto): Subscription
    {
        $subscription = Subscription::findOrFail($dto->id);

        $subscription->id_subscription_category = $dto->categoryId;
        $subscription->title = $dto->title;
        $subscription->percent = $dto->percent;
        $subscription->alias = $dto->alias;
        $subscription->description = $dto->description;
        if ($dto->isCustom !== null) {
            $subscription->is_custom = (bool) $dto->isCustom;
        }
        if ($dto->showReactions !== null) {
            $subscription->show_reactions = (bool) $dto->showReactions;
        }
        if ($dto->showComments !== null) {
            $subscription->show_comments = (bool) $dto->showComments;
        }

        if ($dto->imageFile) {
            if ($subscription->image && Storage::disk('public')->exists($subscription->image)) {
                Storage::disk('public')->delete($subscription->image);
            }
            $imagePath = $dto->imageFile->store('subscriptions', 'public');
            $subscription->image = $imagePath;
        }

        $subscription->save();

        return $subscription;
    }
}
