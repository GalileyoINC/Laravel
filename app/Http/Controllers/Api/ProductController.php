<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Product\GetProductAlertsAction;
use App\Domain\Actions\Product\GetProductListAction;
use App\Domain\Actions\Product\ProcessApplePurchaseAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Refactored Product Controller using DDD Actions
 * Handles product listings, alerts, and Apple purchases
 */
class ProductController extends Controller
{
    public function __construct(
        private readonly GetProductListAction $getProductListAction,
        private readonly GetProductAlertsAction $getProductAlertsAction,
        private readonly ProcessApplePurchaseAction $processApplePurchaseAction
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
