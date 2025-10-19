<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Models\User\User;

class RemoveUserCreditAction
{
    public function execute(int $userId): User
    {
        $user = User::findOrFail($userId);
        $user->bonus_point = 0;
        $user->save();

        return $user->fresh();
    }
}
