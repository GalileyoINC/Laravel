<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Chat\GetChatListAction;
use App\Http\Controllers\Controller;
use App\Models\Communication\ConversationFile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Refactored Chat Controller using DDD Actions
 * Handles all chat functionality: conversations, messages, file uploads, groups
 */
class ChatController extends Controller
{
    public function __construct(
        private readonly GetChatListAction $getChatListAction
    ) {}

    /**
     * Get conversation list (POST /api/v1/chat/list)
     */
    public function list(Request $request): JsonResponse
    {
        return $this->getChatListAction->execute($request->all());
    }

    /**
     * Get conversation messages (POST /api/v1/chat/chat-messages)
     */
    public function chatMessages(Request $request): JsonResponse
    {
        // Implementation for getting chat messages
        return response()->json(['message' => 'Chat messages endpoint not implemented yet']);
    }

    /**
     * Get single conversation view (POST /api/v1/chat/view)
     */
    public function view(Request $request): JsonResponse
    {
        // Implementation for getting conversation view
        return response()->json(['message' => 'Chat view endpoint not implemented yet']);
    }

    /**
     * Upload files to conversation (POST /api/v1/chat/upload)
     */
    public function upload(Request $request): JsonResponse
    {
        // Implementation for file upload
        return response()->json(['message' => 'File upload endpoint not implemented yet']);
    }

    /**
     * Create group chat (POST /api/v1/chat/create-group)
     */
    public function createGroup(Request $request): JsonResponse
    {
        // Implementation for creating group chat
        return response()->json(['message' => 'Create group endpoint not implemented yet']);
    }

    /**
     * Get friend chat conversation (POST /api/v1/chat/get-friend-chat)
     */
    public function getFriendChat(Request $request): JsonResponse
    {
        // Implementation for getting friend chat
        return response()->json(['message' => 'Get friend chat endpoint not implemented yet']);
    }

    /**
     * Get chat file (GET /api/v1/chat/get-file/{id}/{type})
     * This endpoint serves files for download
     */
    public function getFile($id, $type = 'original'): JsonResponse
    {
        try {
            $fileId = (int) $id;
            $fileType = $type ?? 'original';

            $conversationFile = ConversationFile::find($fileId);
            if (! $conversationFile || empty($conversationFile->sizes[$fileType]['name'])) {
                return response()->json([
                    'error' => 'File not found',
                    'code' => 404,
                ], 404);
            }

            // Serve the file directly
            $filePath = $conversationFile->folder_name.'/'.$conversationFile->sizes[$fileType]['name'];
            $fileName = $conversationFile->sizes[$fileType]['name'];

            if (Storage::exists($filePath)) {
                return Storage::download($filePath, $fileName);
            }

            return response()->json([
                'error' => 'File not found on disk',
                'code' => 404,
            ], 404);

        } catch (Exception $e) {
            Log::error('Chat file error: '.$e->getMessage());

            return response()->json([
                'error' => 'File not found or access denied',
                'code' => 404,
            ], 404);
        }
    }
}
