<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Models\User\User;

class VerifyInfluencerAction
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
        $user->is_influencer = true;
        $user->influencer_verified_at = now();
        $user->save();

        return ['success' => true];
    }
}
