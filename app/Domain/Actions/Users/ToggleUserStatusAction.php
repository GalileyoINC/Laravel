<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Models\User\User;

class ToggleUserStatusAction
{
    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function execute(int $userId): array
    {
        $user = User::findOrFail($userId);
        $user->update([
            'status' => $user->status === 1 ? 0 : 1,
        ]);

        return [
            'status' => $user->status,
        ];
    }
}
