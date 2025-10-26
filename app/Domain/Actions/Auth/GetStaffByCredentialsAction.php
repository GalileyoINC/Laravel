<?php

declare(strict_types=1);

namespace App\Domain\Actions\Auth;

use App\Models\Admin\Staff;

class GetStaffByCredentialsAction
{
    public function execute(string $username): ?Staff
    {
        return Staff::where('username', $username)
            ->orWhere('email', $username)
            ->first();
    }
}
