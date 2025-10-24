<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Chat\GetChatListAction;
use App\Http\Controllers\Controller;
use App\Models\Communication\ConversationFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

/**
 * Refactored Chat Controller using DDD Actions
 * Handles all chat functionality: conversations, messages, file uploads, groups
 */
#[OA\Tag(name: 'Chat', description: 'Chat and messaging endpoints')]
class ChatController extends Controller
{
    public function __construct(
        private readonly GetChatListAction $getChatListAction
    ) {}

    /**
     * Get conversation list
     *
     * POST /api/v1/chat/list
     */
    #[OA\Post(
        path: '/api/v1/chat/list',
        description: 'Get paginated list of conversations',
        summary: 'List conversations',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 15),
                    new OA\Property(property: 'type', type: 'string', example: 'all', enum: ['all', 'private', 'group']),
                ]
            )
        ),
        tags: ['Chat'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Conversations list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'meta', properties: [
                            new OA\Property(property: 'current_page', type: 'integer', example: 1),
                            new OA\Property(property: 'last_page', type: 'integer', example: 5),
                            new OA\Property(property: 'per_page', type: 'integer', example: 15),
                            new OA\Property(property: 'total', type: 'integer', example: 75),
                        ], type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function list(Request $request): JsonResponse
    {
        return $this->getChatListAction->execute($request->all());
    }

    /**
     * Get conversation messages
     *
     * POST /api/v1/chat/chat-messages
     */
    #[OA\Post(
        path: '/api/v1/chat/chat-messages',
        description: 'Get messages for a conversation',
        summary: 'Get chat messages',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['conversation_id'],
                properties: [
                    new OA\Property(property: 'conversation_id', type: 'integer', example: 1),
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 20),
                ]
            )
        ),
        tags: ['Chat'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Chat messages',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'message', type: 'string', example: 'Chat messages endpoint not implemented yet'),
                    ]
                )
            ),
        ]
    )]
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
    public function getFile(int $id, string $type = 'original'): Response
    {
        $fileId = (int) $id;
        $fileType = $type;

        $conversationFile = ConversationFile::find($fileId);
        if (! $conversationFile || empty($conversationFile->sizes[$fileType]['name'])) {
            return response()->json([
                'error' => 'File not found on disk',
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
    }
}
