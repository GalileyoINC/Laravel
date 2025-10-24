<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\PrivateFeed\GetPrivateFeedListAction;
use App\Domain\DTOs\PrivateFeed\PrivateFeedCreateRequestDTO;
use App\Domain\Services\PrivateFeed\PrivateFeedServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

/**
 * Refactored PrivateFeed Controller using DDD Actions
 * Handles private feed content management: creation, invites, followers
 */
#[OA\Tag(name: 'Private Feed', description: 'Private feed content management operations')]
class PrivateFeedController extends Controller
{
    public function __construct(
        private readonly GetPrivateFeedListAction $getPrivateFeedListAction,
        private readonly PrivateFeedServiceInterface $privateFeedService
    ) {}

    /**
     * Get private feed list (POST /api/v1/private-feed/index)
     */
    #[OA\Post(
        path: '/api/v1/private-feed/index',
        description: 'Get list of private feeds',
        summary: 'List private feeds',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                    new OA\Property(property: 'search', type: 'string', example: 'feed search'),
                ]
            )
        ),
        tags: ['Private Feed'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Private feeds retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        return $this->getPrivateFeedListAction->execute($request->all());
    }

    /**
     * Create private feed (POST /api/v1/private-feed/create)
     */
    #[OA\Post(
        path: '/api/v1/private-feed/create',
        description: 'Create a new private feed',
        summary: 'Create private feed',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'description'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'My Private Feed'),
                    new OA\Property(property: 'description', type: 'string', example: 'Description of the private feed'),
                    new OA\Property(property: 'is_public', type: 'boolean', example: false),
                    new OA\Property(property: 'invite_emails', type: 'array', items: new OA\Items(type: 'string'), example: ['user@example.com']),
                ]
            )
        ),
        tags: ['Private Feed'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Private feed created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'My Private Feed'),
                        new OA\Property(property: 'description', type: 'string', example: 'Description of the private feed'),
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
    public function create(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $dto = PrivateFeedCreateRequestDTO::fromRequest($request);
        if (! $dto->validate()) {
            return response()->json([
                'errors' => ['Invalid private feed creation request'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $privateFeed = $this->privateFeedService->createPrivateFeed($dto, $user);

        return response()->json($privateFeed->toArray());
    }

    /**
     * Update private feed (POST /api/v1/private-feed/update)
     */
    #[OA\Post(
        path: '/api/v1/private-feed/update',
        description: 'Update an existing private feed',
        summary: 'Update private feed',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id'],
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Updated Private Feed'),
                    new OA\Property(property: 'description', type: 'string', example: 'Updated description'),
                    new OA\Property(property: 'is_public', type: 'boolean', example: false),
                ]
            )
        ),
        tags: ['Private Feed'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Private feed updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'Updated Private Feed'),
                    ]
                )
            ),
        ]
    )]
    public function update(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $id = $request->input('id');
        if (! $id) {
            return response()->json([
                'errors' => ['ID is required'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $dto = PrivateFeedCreateRequestDTO::fromRequest($request);
        if (! $dto->validate()) {
            return response()->json([
                'errors' => ['Invalid private feed update request'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $privateFeed = $this->privateFeedService->updatePrivateFeed($id, $dto, $user);

        return response()->json($privateFeed->toArray());
    }

    /**
     * Delete private feed (POST /api/v1/private-feed/delete)
     */
    #[OA\Post(
        path: '/api/v1/private-feed/delete',
        description: 'Delete a private feed',
        summary: 'Delete private feed',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id'],
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                ]
            )
        ),
        tags: ['Private Feed'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Private feed deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Failed to delete private feed',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Failed to delete private feed'),
                        new OA\Property(property: 'code', type: 'integer', example: 400),
                    ]
                )
            ),
        ]
    )]
    public function delete(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $id = $request->input('id');
        if (! $id) {
            return response()->json([
                'errors' => ['ID is required'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $success = $this->privateFeedService->deletePrivateFeed($id, $user);

        if ($success) {
            return response()->json(['success' => true]);
        }

        return response()->json([
            'error' => 'Failed to delete private feed',
            'code' => 400,
        ], 400);
    }
}
