<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Models\User\User;

class DeleteUserAction
{
    public function execute(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->delete();
    }
}
