<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Domain\DTOs\Users\SetFeedVisibilityDTO;
use App\Models\Subscription\Subscription;

class SetSubscriptionFeedVisibilityAction
{
    public function execute(SetFeedVisibilityDTO $dto): array
    {
        $updated = Subscription::where('id', $dto->subscriptionId)
            ->update(['is_hidden' => $dto->isHidden]) > 0;

        return ['updated' => $updated];
    }
}
