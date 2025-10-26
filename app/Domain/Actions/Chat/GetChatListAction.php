<?php

declare(strict_types=1);

namespace App\Domain\Actions\Chat;

use App\Domain\Services\Chat\ChatServiceInterface;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetChatListAction
{
    public function __construct(
        private readonly ChatServiceInterface $chatService
    ) {}

    public function execute(
        int $page = 1,
        int $limit = 20,
        ?string $search = null,
        string $sortBy = 'updated_at',
        string $sortOrder = 'desc'
    ): LengthAwarePaginator {
        $user = Auth::user();

        if (! $user) {
            Log::error('GetChatListAction: User not authenticated');

            throw new Exception('User not authenticated');
        }

        return $this->chatService->getConversationList($page, $limit, $search, $sortBy, $sortOrder, $user);
    }
}
