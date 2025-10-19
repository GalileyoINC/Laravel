<?php

declare(strict_types=1);

namespace App\Domain\Actions\Subscription;

use App\Domain\DTOs\Subscription\SubscriptionDeactivateRequestDTO;
use App\Models\Subscription\Subscription;

final class DeactivateSubscriptionAction
{
    public function execute(SubscriptionDeactivateRequestDTO $dto): bool
    {
        $subscription = Subscription::findOrFail($dto->id);

        return $subscription->update(['is_active' => false]);
    }
}
