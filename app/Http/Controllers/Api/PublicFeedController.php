<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\PublicFeed\GetPublicFeedOptionsAction;
use App\Domain\DTOs\PublicFeed\PublicFeedImageUploadRequestDTO;
use App\Domain\DTOs\PublicFeed\PublicFeedPublishRequestDTO;
use App\Domain\Services\PublicFeed\PublicFeedServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

/**
 * Refactored PublicFeed Controller using DDD Actions
 * Handles public content publication: subscription targeting, messaging, image uploads
 */
#[OA\Tag(name: 'Public Feed', description: 'Public feed content publication operations')]
class PublicFeedController extends Controller
{
    public function __construct(
        private readonly GetPublicFeedOptionsAction $getPublicFeedOptionsAction,
        private readonly PublicFeedServiceInterface $publicFeedService
    ) {}

    /**
     * Get public feed options (POST /api/v1/public-feed/get-options)
     */
    #[OA\Post(
        path: '/api/v1/public-feed/get-options',
        description: 'Get available options for public feed publication',
        summary: 'Get public feed options',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'feed_type', type: 'string', example: 'news'),
                    new OA\Property(property: 'category', type: 'string', example: 'technology'),
                ]
            )
        ),
        tags: ['Public Feed'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Public feed options retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function getOptions(Request $request): JsonResponse
    {
        return $this->getPublicFeedOptionsAction->execute($request->all());
    }

    /**
     * Send to public feeds (POST /api/v1/public-feed/send)
     */
    #[OA\Post(
        path: '/api/v1/public-feed/send',
        description: 'Publish content to public feeds',
        summary: 'Send to public feeds',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['content', 'target_feeds'],
                properties: [
                    new OA\Property(property: 'content', type: 'string', example: 'This is my public post content'),
                    new OA\Property(property: 'target_feeds', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2, 3]),
                    new OA\Property(property: 'images', type: 'array', items: new OA\Items(type: 'string'), example: ['https://example.com/image1.jpg']),
                    new OA\Property(property: 'tags', type: 'array', items: new OA\Items(type: 'string'), example: ['news', 'update']),
                    new OA\Property(property: 'scheduled_at', type: 'string', format: 'date-time', example: '2024-12-31T10:00:00Z'),
                ]
            )
        ),
        tags: ['Public Feed'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Content published to public feeds successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Content published successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'User not authenticated',
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

        $dto = PublicFeedPublishRequestDTO::fromRequest($request);
        if (! $dto->validate()) {
            return response()->json([
                'errors' => ['Invalid public feed publish request'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $result = $this->publicFeedService->publishToPublicFeeds($dto, $user);

        return response()->json($result);
    }

    /**
     * Upload image for public feed (POST /api/v1/public-feed/image-upload)
     */
    #[OA\Post(
        path: '/api/v1/public-feed/image-upload',
        description: 'Upload image for public feed content',
        summary: 'Upload image',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['image'],
                    properties: [
                        new OA\Property(property: 'image', type: 'string', format: 'binary'),
                        new OA\Property(property: 'caption', type: 'string', example: 'Image caption'),
                        new OA\Property(property: 'alt_text', type: 'string', example: 'Alternative text for accessibility'),
                    ]
                )
            )
        ),
        tags: ['Public Feed'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Image uploaded successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Image uploaded successfully'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'image_url', type: 'string', example: 'https://example.com/uploaded-image.jpg'),
                                new OA\Property(property: 'thumbnail_url', type: 'string', example: 'https://example.com/thumbnail.jpg'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'User not authenticated',
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

        $dto = PublicFeedImageUploadRequestDTO::fromRequest($request);
        if (! $dto->validate()) {
            return response()->json([
                'errors' => ['Invalid image upload request'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $result = $this->publicFeedService->uploadImage($dto, $user);

        return response()->json($result);
    }
}
