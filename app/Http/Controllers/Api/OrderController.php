<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Order\CreateOrderAction;
use App\Domain\Actions\Order\PayOrderAction;
use App\Domain\Services\Order\OrderServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

/**
 * Refactored Order Controller using DDD Actions
 * Handles order creation, payment processing, and Apple purchases
 */
#[OA\Tag(name: 'Orders', description: 'Order management and payment processing operations')]
class OrderController extends Controller
{
    public function __construct(
        private readonly CreateOrderAction $createOrderAction,
        private readonly PayOrderAction $payOrderAction,
        private readonly OrderServiceInterface $orderService
    ) {}

    /**
     * Create new order (POST /api/v1/order/create)
     */
    #[OA\Post(
        path: '/api/v1/order/create',
        description: 'Create a new order',
        summary: 'Create order',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['product_id', 'quantity'],
                properties: [
                    new OA\Property(property: 'product_id', type: 'integer', example: 1),
                    new OA\Property(property: 'quantity', type: 'integer', example: 1),
                    new OA\Property(property: 'payment_method', type: 'string', example: 'credit_card'),
                    new OA\Property(property: 'billing_address', type: 'object'),
                ]
            )
        ),
        tags: ['Orders'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Order created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function create(Request $request): JsonResponse
    {
        return $this->createOrderAction->execute($request->all());
    }

    /**
     * Pay for order (POST /api/v1/order/pay)
     */
    #[OA\Post(
        path: '/api/v1/order/pay',
        description: 'Process payment for an order',
        summary: 'Pay order',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['order_id', 'payment_token'],
                properties: [
                    new OA\Property(property: 'order_id', type: 'integer', example: 1),
                    new OA\Property(property: 'payment_token', type: 'string', example: 'payment_token_123'),
                    new OA\Property(property: 'payment_method', type: 'string', example: 'credit_card'),
                ]
            )
        ),
        tags: ['Orders'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Payment processed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function pay(Request $request): JsonResponse
    {
        return $this->payOrderAction->execute($request->all());
    }

    /**
     * Get test order data (GET /api/v1/order/test)
     */
    #[OA\Get(
        path: '/api/v1/order/test',
        description: 'Get test order data for development',
        summary: 'Get test order',
        tags: ['Orders'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Test order data retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function test(): JsonResponse
    {
        $testData = $this->orderService->getTestOrder();

        return response()->json($testData);
    }
}
