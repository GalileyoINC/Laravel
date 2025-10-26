<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domain\Actions\Chat\GetAdminConversationMessagesAction;
use App\Domain\Actions\Chat\GetAdminConversationsListAction;
use App\Domain\Actions\Chat\GetAdminRecentMessagesAction;
use App\Domain\Actions\Chat\SendAdminMessageAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function __construct(
        private readonly GetAdminConversationsListAction $getAdminConversationsListAction,
        private readonly GetAdminConversationMessagesAction $getAdminConversationMessagesAction,
        private readonly SendAdminMessageAction $sendAdminMessageAction,
        private readonly GetAdminRecentMessagesAction $getAdminRecentMessagesAction,
    ) {}

    public function index(): View
    {
        $conversations = $this->getAdminConversationsListAction->execute(50);

        return view('chat.admin', ['conversations' => $conversations]);
    }

    public function getUnreadCount(): JsonResponse
    {
        return response()->json(['unread_count' => 0]);
    }

    public function getConversations(): JsonResponse
    {
        $conversations = $this->getAdminConversationsListAction->execute(100);

        return response()->json([
            'status' => 'success',
            'data' => $conversations->items(),
        ]);
    }

    public function getMessages(int $conversationId): JsonResponse
    {
        $messages = $this->getAdminConversationMessagesAction->execute($conversationId);

        return response()->json([
            'status' => 'success',
            'data' => $messages,
        ]);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'conversation_id' => 'required|integer|exists:conversation,id',
            'message' => 'required|string|max:5000',
        ]);

        $message = $this->sendAdminMessageAction->execute(
            conversationId: (int) $request->input('conversation_id'),
            message: (string) $request->input('message')
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => $message,
        ], 201);
    }

    public function getRecentMessages(): JsonResponse
    {
        $messages = $this->getAdminRecentMessagesAction->execute(50);

        return response()->json([
            'status' => 'success',
            'data' => $messages->reverse(),
        ]);
    }
}
