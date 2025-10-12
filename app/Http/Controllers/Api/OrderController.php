<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Order\CreateOrderAction;
use App\Actions\Order\PayOrderAction;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Refactored Order Controller using DDD Actions
 * Handles order creation, payment processing, and Apple purchases
 */
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
    public function create(Request $request): JsonResponse
    {
        return $this->createOrderAction->execute($request->all());
    }

    /**
     * Pay for order (POST /api/v1/order/pay)
     */
    public function pay(Request $request): JsonResponse
    {
        return $this->payOrderAction->execute($request->all());
    }

    /**
     * Get test order data (GET /api/v1/order/test)
     */
    public function test(): JsonResponse
    {
        $testData = $this->orderService->getTestOrder();

        return response()->json($testData);
    }
}
