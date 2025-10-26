<?php

declare(strict_types=1);

namespace App\Domain\Actions\Chat;

use App\Domain\Services\Chat\ChatServiceInterface;
use App\Models\Communication\Conversation;
use App\Models\Communication\ConversationUser;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetFriendChatAction
{
    public function __construct(
        private readonly ChatServiceInterface $chatService
    ) {}

    public function execute(int $friendId): Conversation
    {
        $user = Auth::user();

        if (! $user) {
            Log::error('GetFriendChatAction: User not authenticated');

            throw new Exception('User not authenticated');
        }

        // Find existing conversation between exactly these two users
        $conversationIds = ConversationUser::where('id_user', $user->id)
            ->orWhere('id_user', $friendId)
            ->groupBy('id_conversation')
            ->havingRaw('COUNT(DISTINCT id_user) = 2')
            ->pluck('id_conversation');

        $conversation = null;

        if ($conversationIds->isNotEmpty()) {
            // Get all conversation IDs and check which ones have exactly these two users
            $conversations = Conversation::whereIn('id', $conversationIds)
                ->with('users')
                ->get();

            foreach ($conversations as $tempConv) {
                $userIds = $tempConv->users->pluck('id')->toArray();

                if (count($userIds) === 2 && in_array($user->id, $userIds) && in_array($friendId, $userIds)) {
                    $conversation = $tempConv;
                    break;
                }
            }
        }

        // If no conversation exists, create one
        if (! $conversation) {
            $conversation = Conversation::create();
            $conversation->users()->attach([$user->id, $friendId]);
        }

        $conversation->load([
            'users:id,first_name,last_name',
            'conversation_messages' => function ($q) {
                $q->with('user:id,first_name,last_name')->latest()->limit(20);
            },
        ]);

        return $conversation;
    }
}
