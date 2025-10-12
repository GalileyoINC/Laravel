<?php

declare(strict_types=1);

namespace App\Modules\Communication\Application\Actions\Chat;

use App\DTOs\Chat\ChatListRequestDTO;
use App\Services\Chat\ChatServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetChatListAction
{
    public function __construct(
        private readonly ChatServiceInterface $chatService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = ChatListRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid chat list request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $conversationList = $this->chatService->getConversationList($dto, $user);

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
