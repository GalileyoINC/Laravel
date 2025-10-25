<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Product\GetProductAlertsAction;
use App\Domain\Actions\Product\GetProductAlertsWithMapAction;
use App\Domain\Actions\Product\GetProductListAction;
use App\Domain\Actions\Product\ProcessApplePurchaseAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductAlertMapRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

/**
 * Refactored Product Controller using DDD Actions
 * Handles product listings, alerts, and Apple purchases
 */
#[OA\Tag(name: 'Products', description: 'Product management and purchase operations')]
class ProductController extends Controller
{
    public function __construct(
        private readonly GetProductListAction $getProductListAction,
        private readonly GetProductAlertsAction $getProductAlertsAction,
        private readonly GetProductAlertsWithMapAction $getProductAlertsWithMapAction,
        private readonly ProcessApplePurchaseAction $processApplePurchaseAction
    ) {}

    /**
     * Get product list (POST /api/v1/product/list)
     */
    #[OA\Post(
        path: '/api/v1/product/list',
        description: 'Get list of available products',
        summary: 'List products',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                    new OA\Property(property: 'category', type: 'string', example: 'electronics'),
                    new OA\Property(property: 'search', type: 'string', example: 'product search'),
                ]
            )
        ),
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Products retrieved successfully',
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
    public function list(Request $request): JsonResponse
    {
        return $this->getProductListAction->execute($request->all());
    }

    /**
     * Get product alerts (POST /api/v1/product/alerts)
     */
    #[OA\Post(
        path: '/api/v1/product/alerts',
        description: 'Get product alerts and notifications',
        summary: 'Get product alerts',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'product_id', type: 'integer', example: 1),
                    new OA\Property(property: 'alert_type', type: 'string', example: 'price_drop'),
                ]
            )
        ),
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product alerts retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                    ]
                )
            ),
        ]
    )]
    public function alerts(Request $request): JsonResponse
    {
        return $this->getProductAlertsAction->execute($request->all());
    }

    /**
     * Process Apple purchase (POST /api/v1/product/purchase)
     */
    #[OA\Post(
        path: '/api/v1/product/purchase',
        description: 'Process Apple In-App Purchase',
        summary: 'Process Apple purchase',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['product_id', 'receipt_data'],
                properties: [
                    new OA\Property(property: 'product_id', type: 'string', example: 'com.example.product'),
                    new OA\Property(property: 'receipt_data', type: 'string', example: 'base64_encoded_receipt'),
                    new OA\Property(property: 'transaction_id', type: 'string', example: 'transaction_123'),
                ]
            )
        ),
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Purchase processed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Purchase verified successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid purchase data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Invalid receipt data'),
                    ]
                )
            ),
        ]
    )]
    public function purchase(Request $request): JsonResponse
    {
        return $this->processApplePurchaseAction->execute($request->all());
    }

    /**
     * Get product alerts with map data (POST /api/v1/product/alerts/map)
     */
    #[OA\Post(
        path: '/api/v1/product/alerts/map',
        description: 'Get product alerts with geographic map data',
        summary: 'Get alerts for map display',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'limit', type: 'integer', example: 20),
                    new OA\Property(property: 'offset', type: 'integer', example: 0),
                    new OA\Property(property: 'severity', type: 'string', example: 'high', enum: ['critical', 'high', 'medium', 'low']),
                    new OA\Property(property: 'category', type: 'string', example: 'emergency'),
                    new OA\Property(property: 'bounds', type: 'object', properties: [
                        new OA\Property(property: 'north', type: 'number', example: 40.7589),
                        new OA\Property(property: 'south', type: 'number', example: 40.7489),
                        new OA\Property(property: 'east', type: 'number', example: -73.9857),
                        new OA\Property(property: 'west', type: 'number', example: -73.9957),
                    ]),
                    new OA\Property(property: 'filter', type: 'object', properties: [
                        new OA\Property(property: 'type', type: 'integer', example: 1),
                        new OA\Property(property: 'status', type: 'integer', example: 1),
                        new OA\Property(property: 'active_only', type: 'boolean', example: true),
                    ]),
                ]
            )
        ),
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Map alerts retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'meta', properties: [
                            new OA\Property(property: 'total', type: 'integer', example: 15),
                            new OA\Property(property: 'limit', type: 'integer', example: 20),
                            new OA\Property(property: 'offset', type: 'integer', example: 0),
                        ], type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request parameters',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid request parameters'),
                        new OA\Property(property: 'errors', type: 'array', items: new OA\Items(type: 'string')),
                    ]
                )
            ),
        ]
    )]
    public function alertsWithMap(ProductAlertMapRequest $request): JsonResponse
    {
        return $this->getProductAlertsWithMapAction->execute($request->validated());
    }
}
