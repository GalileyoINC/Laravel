<?php

declare(strict_types=1);

namespace App\Domain\Actions\Chat;

use App\Models\Communication\Conversation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetAdminConversationsListAction
{
    public function execute(int $limit = 50): LengthAwarePaginator
    {
        return Conversation::withCount(['users', 'conversation_messages'])
            ->with('users:id,first_name,last_name')
            ->orderBy('updated_at', 'desc')
            ->paginate($limit);
    }
}
