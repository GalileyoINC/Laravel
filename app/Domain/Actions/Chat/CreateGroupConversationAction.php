<?php

declare(strict_types=1);

namespace App\Domain\Actions\Chat;

use App\Models\Communication\Conversation;
use Exception;
use Illuminate\Support\Facades\Auth;

class CreateGroupConversationAction
{
    public function execute(string $name, array $userIds): Conversation
    {
        $user = Auth::user();

        if (! $user) {
            throw new Exception('User not authenticated');
        }

        // Add current user to participants
        if (! in_array($user->id, $userIds)) {
            $userIds[] = $user->id;
        }

        $conversation = Conversation::create();
        $conversation->users()->attach($userIds);
        $conversation->update(['name' => $name]);

        $conversation->load('users:id,first_name,last_name');

        return $conversation;
    }
}
