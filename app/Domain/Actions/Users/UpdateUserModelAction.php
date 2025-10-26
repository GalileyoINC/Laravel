<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Models\User\User;

class UpdateUserModelAction
{
    public function execute(User $user, array $data): void
    {
        $user->update($data);
    }
}

