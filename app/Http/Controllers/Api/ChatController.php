<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Chat\CreateGroupConversationAction;
use App\Domain\Actions\Chat\GetChatListAction;
use App\Domain\Actions\Chat\GetConversationAction;
use App\Domain\Actions\Chat\GetConversationMessagesAction;
use App\Domain\Actions\Chat\GetFriendChatAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\GetFriendChatRequest;
use App\Http\Resources\ConversationResource;
use App\Models\Communication\ConversationFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * Refactored Chat Controller using DDD Actions
 * Handles all chat functionality: conversations, messages, file uploads, groups
 */
#[OA\Tag(name: 'Chat', description: 'Chat and messaging endpoints')]
class ChatController extends Controller
{
    public function __construct(
        private readonly GetChatListAction $getChatListAction,
        private readonly GetFriendChatAction $getFriendChatAction,
        private readonly GetConversationMessagesAction $getConversationMessagesAction,
        private readonly GetConversationAction $getConversationAction,
        private readonly CreateGroupConversationAction $createGroupConversationAction,
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
        $conversations = $this->getChatListAction->execute(
            page: (int) $request->input('page', 1),
            limit: (int) $request->input('per_page', 20),
            search: $request->input('search'),
            sortBy: $request->input('sort_by', 'updated_at'),
            sortOrder: $request->input('sort_order', 'desc')
        );

        return response()->json([
            'status' => 'success',
            'data' => $conversations->items(),
            'meta' => [
                'current_page' => $conversations->currentPage(),
                'last_page' => $conversations->lastPage(),
                'per_page' => $conversations->perPage(),
                'total' => $conversations->total(),
            ],
        ]);
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
                    ]
                )
            ),
        ]
    )]
    public function chatMessages(Request $request): JsonResponse
    {
        $request->validate([
            'conversation_id' => 'required|integer|exists:conversation,id',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $messages = $this->getConversationMessagesAction->execute(
            conversationId: (int) $request->input('conversation_id'),
            perPage: (int) $request->input('per_page', 20),
            page: (int) $request->input('page', 1)
        );

        return response()->json([
            'status' => 'success',
            'data' => $messages->items(),
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ],
        ]);
    }

    /**
     * Get single conversation view (POST /api/v1/chat/view)
     */
    public function view(Request $request): JsonResponse
    {
        $request->validate([
            'conversation_id' => 'required|integer|exists:conversation,id',
        ]);

        $user = Auth::user();
        $conversation = $this->getConversationAction->execute((int) $request->input('conversation_id'));

        if (! $conversation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Conversation not found',
            ], 404);
        }

        // Check if user is participant
        $isParticipant = $conversation->users->contains('id', $user->id);
        if (! $isParticipant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied',
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $conversation,
        ]);
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
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:user,id',
        ]);

        $conversation = $this->createGroupConversationAction->execute(
            name: (string) $request->input('name'),
            userIds: $request->input('user_ids')
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Group chat created successfully',
            'data' => $conversation,
        ], 201);
    }

    /**
     * Get friend chat conversation (POST /api/v1/chat/get-friend-chat)
     */
    public function getFriendChat(GetFriendChatRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $conversation = $this->getFriendChatAction->execute((int) $validated['friend_id']);

        return response()->json([
            'status' => 'success',
            'data' => new ConversationResource($conversation),
        ]);
    }

    /**
     * Send a new message to conversation (POST /api/v1/chat/send)
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'conversation_id' => 'required|integer|exists:conversation,id',
            'message' => 'required|string|max:5000',
        ]);

        $user = Auth::user();

        $dto = new \App\Domain\DTOs\Chat\SendMessageDTO(
            conversationId: $request->input('conversation_id'),
            userId: $user->id,
            message: $request->input('message'),
        );

        $action = new \App\Domain\Actions\Chat\SendMessageAction();
        $message = $action->execute($dto);

        // Load user relationship with proper column selection
        $message->load('user');

        // Format response data
        $messageData = [
            'id' => $message->id,
            'id_conversation' => $message->id_conversation,
            'id_user' => $message->id_user,
            'message' => $message->message,
            'created_at' => $message->created_at,
            'user' => $message->user ? [
                'id' => $message->user->id,
                'first_name' => $message->user->first_name,
                'last_name' => $message->user->last_name,
            ] : null,
        ];

        // Broadcast to admin panel
        event(new \App\Events\NewLiveChatMessage($messageData));

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => $messageData,
        ], 201);
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
