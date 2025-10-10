<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Order\CreateOrderAction;
use App\Actions\Order\PayOrderAction;
use App\Services\Order\OrderServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Refactored Order Controller using DDD Actions
 * Handles order creation, payment processing, and Apple purchases
 */
class OrderController extends Controller
{
    public function __construct(
        private CreateOrderAction $createOrderAction,
        private PayOrderAction $payOrderAction,
        private OrderServiceInterface $orderService
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