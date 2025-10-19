<?php

declare(strict_types=1);

namespace App\Domain\Actions\Subscription;

use App\Domain\DTOs\Subscription\SubscriptionActivateRequestDTO;
use App\Models\Subscription\Subscription;

final class ActivateSubscriptionAction
{
    public function execute(SubscriptionActivateRequestDTO $dto): bool
    {
        $subscription = Subscription::findOrFail($dto->id);

        return $subscription->update(['is_active' => true]);
    }
}
