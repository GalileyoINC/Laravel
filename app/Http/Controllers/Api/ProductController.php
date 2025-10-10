<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Product\GetProductListAction;
use App\Actions\Product\GetProductAlertsAction;
use App\Actions\Product\ProcessApplePurchaseAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Refactored Product Controller using DDD Actions
 * Handles product listings, alerts, and Apple purchases
 */
class ProductController extends Controller
{
    public function __construct(
        private GetProductListAction $getProductListAction,
        private GetProductAlertsAction $getProductAlertsAction,
        private ProcessApplePurchaseAction $processApplePurchaseAction
    ) {}

    /**
     * Get product list (POST /api/v1/product/list)
     */
    public function list(Request $request): JsonResponse
    {
        return $this->getProductListAction->execute($request->all());
    }

    /**
     * Get product alerts (POST /api/v1/product/alerts)
     */
    public function alerts(Request $request): JsonResponse
    {
        return $this->getProductAlertsAction->execute($request->all());
    }

    /**
     * Process Apple purchase (POST /api/v1/product/purchase)
     */
    public function purchase(Request $request): JsonResponse
    {
        return $this->processApplePurchaseAction->execute($request->all());
    }
}