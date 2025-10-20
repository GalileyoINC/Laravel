<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Models\User\User;
use RuntimeException;

class LoginAsUserAction
{
    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function execute(int $adminId, int $targetUserId): array
    {
        $admin = User::findOrFail($adminId);
        if ($admin->role !== 1) {
            throw new RuntimeException('Unauthorized action.');
        }

        $user = User::findOrFail($targetUserId);
        $user->admin_token = \Illuminate\Support\Str::random(60);
        $user->save();

        return [
            'token' => $user->admin_token,
        ];
    }
}
