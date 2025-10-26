<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Chat\GetChatListAction;
use App\Http\Controllers\Controller;
use App\Models\Communication\ConversationFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $conversationId = $request->input('conversation_id');
        $perPage = $request->input('per_page', 20);
        $page = $request->input('page', 1);

        $messages = \App\Models\Communication\ConversationMessage::where('id_conversation', $conversationId)
            ->with(['user:id,first_name,last_name'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

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
        $conversationId = $request->input('conversation_id');

        $conversation = \App\Models\Communication\Conversation::with([
            'users:id,first_name,last_name',
            'conversation_messages' => function ($q) {
                $q->latest()->limit(20);
            },
            'conversation_messages.user:id,first_name,last_name',
        ])->find($conversationId);

        if (!$conversation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Conversation not found',
            ], 404);
        }

        // Check if user is participant
        $isParticipant = $conversation->users->contains('id', $user->id);
        if (!$isParticipant) {
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

        $user = Auth::user();
        $userIds = $request->input('user_ids');

        // Add current user to participants
        $userIds[] = $user->id;
        $userIds = array_unique($userIds);

        // Create conversation
        $conversation = \App\Models\Communication\Conversation::create();

        // Attach users to conversation
        $conversation->users()->attach($userIds);

        // Load with users
        $conversation->load('users:id,first_name,last_name');

        return response()->json([
            'status' => 'success',
            'message' => 'Group chat created successfully',
            'data' => $conversation,
        ], 201);
    }

    /**
     * Get friend chat conversation (POST /api/v1/chat/get-friend-chat)
     */
    public function getFriendChat(Request $request): JsonResponse
    {
        $request->validate([
            'friend_id' => 'required|integer|exists:user,id',
        ]);

        $user = Auth::user();
        $friendId = $request->input('friend_id');

        // Find existing conversation between users
        $conversation = \App\Models\Communication\Conversation::whereHas('users', function ($q) use ($user, $friendId) {
            $q->where('id_user', $user->id);
        })->whereHas('users', function ($q) use ($friendId) {
            $q->where('id_user', $friendId);
        })->whereHas('users', function ($q) {
            $q->havingRaw('COUNT(DISTINCT id_user) = 2');
        })->first();

        // If no conversation exists, create one
        if (!$conversation) {
            $conversation = \App\Models\Communication\Conversation::create();
            $conversation->users()->attach([$user->id, $friendId]);
        }

        $conversation->load([
            'users:id,first_name,last_name',
            'conversation_messages.user:id,first_name,last_name',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $conversation,
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

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => $message->load('user:id,first_name,last_name'),
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
