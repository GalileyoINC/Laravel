<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Models\User\User;

class GetUserDetailAction
{
    public function execute(int $userId): User
    {
        /** @var User $user */
        $user = User::with(['phoneNumbers', 'creditCards', 'subscriptions'])->findOrFail($userId);

        // Ensure relations are loaded
        $user->load(['creditCards', 'subscriptions']);

        return $user;
    }
}
