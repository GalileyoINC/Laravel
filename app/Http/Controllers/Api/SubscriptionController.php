<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Subscription\SetSubscriptionAction;
use App\Domain\DTOs\Subscription\FeedOptionsDTO;
use App\Domain\DTOs\Subscription\MarketstackSubscriptionDTO;
use App\Domain\Services\Subscription\SubscriptionServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Communication\SmsPoolPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

/**
 * Refactored Subscription Controller using DDD Actions
 * Handles feed subscriptions, categories, and Marketstack subscriptions
 */
#[OA\Tag(name: 'Subscriptions', description: 'Feed subscription and management operations')]
class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SetSubscriptionAction $setSubscriptionAction,
        private readonly SubscriptionServiceInterface $subscriptionService
    ) {}

    /**
     * Set subscription status (POST /api/v1/feed/set)
     */
    #[OA\Post(
        path: '/api/v1/feed/set',
        description: 'Set subscription status for a feed',
        summary: 'Set subscription',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['feed_id', 'status'],
                properties: [
                    new OA\Property(property: 'feed_id', type: 'integer', example: 1),
                    new OA\Property(property: 'status', type: 'string', example: 'subscribed'),
                    new OA\Property(property: 'notifications_enabled', type: 'boolean', example: true),
                ]
            )
        ),
        tags: ['Subscriptions'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Subscription status updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Subscription updated successfully'),
                    ]
                )
            ),
        ]
    )]
    public function set(Request $request): JsonResponse
    {
        return $this->setSubscriptionAction->execute($request->all());
    }

    /**
     * Set satellite subscription status (POST /api/v1/feed/satellite-set)
     */
    #[OA\Post(
        path: '/api/v1/feed/satellite-set',
        description: 'Set satellite subscription status',
        summary: 'Set satellite subscription',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['feed_id', 'status'],
                properties: [
                    new OA\Property(property: 'feed_id', type: 'integer', example: 1),
                    new OA\Property(property: 'status', type: 'string', example: 'subscribed'),
                    new OA\Property(property: 'notifications_enabled', type: 'boolean', example: true),
                ]
            )
        ),
        tags: ['Subscriptions'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Satellite subscription status updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Satellite subscription updated successfully'),
                    ]
                )
            ),
        ]
    )]
    public function satelliteSet(Request $request): JsonResponse
    {
        $data = $request->all();
        $data['sub_type'] = 'satellite';

        return $this->setSubscriptionAction->execute($data);
    }

    /**
     * Get feed categories (GET /api/v1/feed/category)
     */
    #[OA\Get(
        path: '/api/v1/feed/category',
        description: 'Get available feed categories',
        summary: 'Get feed categories',
        tags: ['Subscriptions'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Feed categories retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'categories', type: 'array', items: new OA\Items(type: 'object')),
                    ]
                )
            ),
        ]
    )]
    public function category(): JsonResponse
    {
        $categories = $this->subscriptionService->getFeedCategories();

        return response()->json($categories);
    }

    /**
     * Get feed list (GET /api/v1/feed/index)
     */
    #[OA\Get(
        path: '/api/v1/feed/index',
        description: 'Get list of available feeds',
        summary: 'Get feed list',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'category',
                description: 'Filter by category',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'technology')
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Page number',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        tags: ['Subscriptions'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Feed list retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'feeds', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $dto = FeedOptionsDTO::fromRequest($request);
        $user = Auth::user();
        $feeds = $this->subscriptionService->getFeedList($dto, $user);

        return response()->json($feeds);
    }

    /**
     * Get satellite feed list (GET /api/v1/feed/satellite-index)
     */
    #[OA\Get(
        path: '/api/v1/feed/satellite-index',
        description: 'Get list of satellite feeds',
        summary: 'Get satellite feed list',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'category',
                description: 'Filter by category',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'satellite')
            ),
        ],
        tags: ['Subscriptions'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Satellite feed list retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'feeds', type: 'array', items: new OA\Items(type: 'object')),
                    ]
                )
            ),
        ]
    )]
    public function satelliteIndex(Request $request): JsonResponse
    {
        $dto = FeedOptionsDTO::fromRequest($request);
        $user = Auth::user();
        $feeds = $this->subscriptionService->getSatelliteFeedList($dto, $user);

        return response()->json($feeds);
    }

    /**
     * Add marketstack index subscription (POST /api/v1/feed/add-own-marketstack-indx-subscription)
     */
    #[OA\Post(
        path: '/api/v1/feed/add-own-marketstack-indx-subscription',
        description: 'Add Marketstack index subscription',
        summary: 'Add Marketstack index subscription',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['symbol', 'api_key'],
                properties: [
                    new OA\Property(property: 'symbol', type: 'string', example: 'AAPL'),
                    new OA\Property(property: 'api_key', type: 'string', example: 'your_marketstack_api_key'),
                    new OA\Property(property: 'name', type: 'string', example: 'Apple Inc.'),
                ]
            )
        ),
        tags: ['Subscriptions'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Marketstack index subscription added successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
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
                    ]
                )
            ),
        ]
    )]
    public function addOwnMarketstackIndxSubscription(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $data = $request->all();
        $data['type'] = 'indx';
        $dto = MarketstackSubscriptionDTO::fromArray($data);

        if (! $dto->validate()) {
            return response()->json(['errors' => ['Invalid marketstack subscription request']], 400);
        }

        $result = $this->subscriptionService->addMarketstackSubscription($dto, $user);

        return response()->json($result);
    }

    /**
     * Add marketstack ticker subscription (POST /api/v1/feed/add-own-marketstack-ticker-subscription)
     */
    #[OA\Post(
        path: '/api/v1/feed/add-own-marketstack-ticker-subscription',
        description: 'Add Marketstack ticker subscription',
        summary: 'Add Marketstack ticker subscription',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['symbol', 'api_key'],
                properties: [
                    new OA\Property(property: 'symbol', type: 'string', example: 'AAPL'),
                    new OA\Property(property: 'api_key', type: 'string', example: 'your_marketstack_api_key'),
                    new OA\Property(property: 'name', type: 'string', example: 'Apple Inc.'),
                ]
            )
        ),
        tags: ['Subscriptions'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Marketstack ticker subscription added successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function addOwnMarketstackTickerSubscription(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $data = $request->all();
        $data['type'] = 'ticker';
        $dto = MarketstackSubscriptionDTO::fromArray($data);

        if (! $dto->validate()) {
            return response()->json(['errors' => ['Invalid marketstack subscription request']], 400);
        }

        $result = $this->subscriptionService->addMarketstackSubscription($dto, $user);

        return response()->json($result);
    }

    /**
     * Get feed options (GET /api/v1/feed/options)
     */
    #[OA\Get(
        path: '/api/v1/feed/options',
        description: 'Get available feed options and settings',
        summary: 'Get feed options',
        tags: ['Subscriptions'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Feed options retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'options', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function options(): JsonResponse
    {
        $options = $this->subscriptionService->getFeedOptions();

        return response()->json($options);
    }

    /**
     * Delete private feed (POST /api/v1/feed/delete-private-feed)
     */
    #[OA\Post(
        path: '/api/v1/feed/delete-private-feed',
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
        tags: ['Subscriptions'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Private feed deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Private feed deleted successfully'),
                    ]
                )
            ),
        ]
    )]
    public function deletePrivateFeed(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $id = $request->input('id');
        if (! $id) {
            return response()->json(['errors' => ['ID is required']], 400);
        }

        $result = $this->subscriptionService->deletePrivateFeed($id, $user);

        return response()->json($result);
    }

    /**
     * Get image from SMS pool photo (GET /api/v1/feed/get-image)
     */
    #[OA\Get(
        path: '/api/v1/feed/get-image',
        description: 'Get image from SMS pool photo',
        summary: 'Get image',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Image ID',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'type',
                description: 'Image type',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'normal')
            ),
        ],
        tags: ['Subscriptions'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Image retrieved successfully',
                content: new OA\MediaType(
                    mediaType: 'image/jpeg',
                    schema: new OA\Schema(type: 'string', format: 'binary')
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Image not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Image not found'),
                    ]
                )
            ),
        ]
    )]
    public function getImage(Request $request): \Illuminate\Http\Response|JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $id = $request->query('id');
        $type = $request->query('type', 'normal');

        if (! $id) {
            return response()->json(['error' => 'Image ID is required'], 400);
        }

        $smsPoolPhoto = SmsPoolPhoto::find($id);

        if (! $smsPoolPhoto) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        /** @var SmsPoolPhoto $smsPoolPhoto */
        // Get sizes from JSON column
        $sizes = $smsPoolPhoto->sizes;

        if (empty($sizes) || empty($sizes[$type]['name'])) {
            return response()->json(['error' => 'Image type not found'], 404);
        }

        $filePath = $smsPoolPhoto->folder_name.'/'.$sizes[$type]['name'];

        // Check if file exists in storage
        if (! Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'Image file not found on disk'], 404);
        }

        // Return the file as a response
        return response()->file(
            Storage::disk('public')->path($filePath),
            [
                'Content-Type' => 'image/jpeg',
                'Content-Disposition' => 'inline; filename="'.$sizes[$type]['name'].'"',
            ]
        );
    }
}
