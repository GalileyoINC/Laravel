<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\AllSendForm\GetAllSendOptionsAction;
use App\Domain\DTOs\AllSendForm\AllSendBroadcastRequestDTO;
use App\Domain\DTOs\AllSendForm\AllSendImageUploadRequestDTO;
use App\Domain\Services\AllSendForm\AllSendFormServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

/**
 * Refactored AllSendForm Controller using DDD Actions
 * Handles content broadcasting: bulk messages, scheduling, file uploads
 */
#[OA\Tag(name: 'Broadcasting', description: 'Content broadcasting and messaging endpoints')]
class AllSendFormController extends Controller
{
    public function __construct(
        private readonly GetAllSendOptionsAction $getAllSendOptionsAction,
        private readonly AllSendFormServiceInterface $allSendFormService
    ) {}

    /**
     * Get broadcasting options
     *
     * POST /api/v1/all-send-form/get-options
     */
    #[OA\Post(
        path: '/api/v1/all-send-form/get-options',
        description: 'Get available broadcasting options and settings',
        summary: 'Get broadcast options',
        security: [['sanctum' => []]],
        tags: ['Broadcasting'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Broadcasting options',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'User not authenticated'),
                        new OA\Property(property: 'code', type: 'integer', example: 401),
                    ]
                )
            ),
        ]
    )]
    public function getOptions(Request $request): JsonResponse
    {
        return $this->getAllSendOptionsAction->execute($request->all());
    }

    /**
     * Send broadcast message
     *
     * POST /api/v1/all-send-form/send
     */
    #[OA\Post(
        path: '/api/v1/all-send-form/send',
        description: 'Send broadcast message to multiple recipients',
        summary: 'Send broadcast',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['message', 'recipients'],
                properties: [
                    new OA\Property(property: 'message', type: 'string', example: 'Hello everyone!'),
                    new OA\Property(property: 'recipients', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2, 3]),
                    new OA\Property(property: 'schedule_at', type: 'string', format: 'date-time', example: '2024-12-25T10:00:00Z'),
                    new OA\Property(property: 'images', type: 'array', items: new OA\Items(type: 'string'), example: ['image1.jpg', 'image2.jpg']),
                ]
            )
        ),
        tags: ['Broadcasting'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Broadcast sent successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Broadcast sent successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'errors', type: 'array', items: new OA\Items(type: 'string')),
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid request parameters'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'User not authenticated'),
                        new OA\Property(property: 'code', type: 'integer', example: 401),
                    ]
                )
            ),
        ]
    )]
    public function send(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $dto = AllSendBroadcastRequestDTO::fromRequest($request);
        if (! $dto->validate()) {
            return response()->json([
                'errors' => ['Invalid broadcast request'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $result = $this->allSendFormService->sendBroadcast($dto, $user);

        return response()->json($result);
    }

    /**
     * Upload image for broadcast
     *
     * POST /api/v1/all-send-form/image-upload
     */
    #[OA\Post(
        path: '/api/v1/all-send-form/image-upload',
        description: 'Upload image for broadcast message',
        summary: 'Upload image',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['image'],
                    properties: [
                        new OA\Property(property: 'image', type: 'string', format: 'binary', description: 'Image file to upload'),
                        new OA\Property(property: 'alt_text', type: 'string', example: 'Description of the image'),
                    ]
                )
            )
        ),
        tags: ['Broadcasting'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Image uploaded successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', properties: [
                            new OA\Property(property: 'image_id', type: 'integer', example: 123),
                            new OA\Property(property: 'image_url', type: 'string', example: 'https://example.com/images/uploaded.jpg'),
                            new OA\Property(property: 'filename', type: 'string', example: 'image.jpg'),
                        ], type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'errors', type: 'array', items: new OA\Items(type: 'string')),
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid request parameters'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'User not authenticated'),
                        new OA\Property(property: 'code', type: 'integer', example: 401),
                    ]
                )
            ),
        ]
    )]
    public function imageUpload(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $dto = AllSendImageUploadRequestDTO::fromRequest($request);
        if (! $dto->validate()) {
            return response()->json([
                'errors' => ['Invalid image upload request'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $result = $this->allSendFormService->uploadImage($dto, $user);

        return response()->json($result);
    }

    /**
     * Delete image
     *
     * POST /api/v1/all-send-form/image-delete
     */
    #[OA\Post(
        path: '/api/v1/all-send-form/image-delete',
        description: 'Delete uploaded image',
        summary: 'Delete image',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id_image'],
                properties: [
                    new OA\Property(property: 'id_image', type: 'integer', example: 123, description: 'Image ID to delete'),
                ]
            )
        ),
        tags: ['Broadcasting'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Image deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Image deleted successfully'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'errors', type: 'array', items: new OA\Items(type: 'string')),
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid request parameters'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'User not authenticated'),
                        new OA\Property(property: 'code', type: 'integer', example: 401),
                    ]
                )
            ),
        ]
    )]
    public function imageDelete(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $imageId = $request->input('id_image');
        if (! $imageId) {
            return response()->json([
                'errors' => ['Image ID is required'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $result = $this->allSendFormService->deleteImage($imageId, $user);

        return response()->json($result);
    }
}
