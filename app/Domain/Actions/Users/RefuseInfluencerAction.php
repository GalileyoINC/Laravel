<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Models\User\User;
use Illuminate\Http\JsonResponse;

class RefuseInfluencerAction
{
    public function execute(int $userId): JsonResponse
    {
        $user = User::findOrFail($userId);
        $user->is_influencer = false;
        $user->influencer_verified_at = null;
        $user->save();

        return response()->json(['success' => true]);
    }
}
