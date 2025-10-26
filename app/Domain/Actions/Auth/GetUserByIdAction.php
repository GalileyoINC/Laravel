<?php

declare(strict_types=1);

namespace App\Domain\Actions\Auth;

use App\Models\User\User;

class GetUserByIdAction
{
    public function execute(int $userId): ?User
    {
        return User::find($userId);
    }
}
