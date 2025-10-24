<?php

declare(strict_types=1);

namespace App\Domain\Actions\Chat;

use App\Domain\Services\Chat\ChatServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetChatListAction
{
    public function __construct(
        private readonly ChatServiceInterface $chatService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        try {
            $page = $data['page'] ?? 1;
            $limit = $data['limit'] ?? 20;
            $search = $data['search'] ?? null;
            $sortBy = $data['sort_by'] ?? 'updated_at';
            $sortOrder = $data['sort_order'] ?? 'desc';

            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $conversationList = $this->chatService->getConversationList($page, $limit, $search, $sortBy, $sortOrder, $user);

            return response()->json($conversationList->toArray());

        } catch (Exception $e) {
            Log::error('GetChatListAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
