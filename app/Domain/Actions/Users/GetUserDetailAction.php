<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Models\User\User;

class GetUserDetailAction
{
    public function execute(int $userId): User
    {
        return User::with(['phoneNumbers', 'creditCards', 'subscriptions'])->findOrFail($userId);
    }
}
