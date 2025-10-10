<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\CreditCard\GetCreditCardListAction;
use App\Actions\CreditCard\CreateCreditCardAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Refactored CreditCard Controller using DDD Actions
 * Handles credit card operations: list, create, update, set preferred, delete
 */
class CreditCardController extends Controller
{
    public function __construct(
        private GetCreditCardListAction $getCreditCardListAction,
        private CreateCreditCardAction $createCreditCardAction
    ) {}

    /**
     * Get credit card list (POST /api/v1/credit-card/list)
     */
    public function list(Request $request): JsonResponse
    {
        return $this->getCreditCardListAction->execute($request->all());
    }

    /**
     * Create credit card (POST /api/v1/credit-card/create)
     */
    public function create(Request $request): JsonResponse
    {
        return $this->createCreditCardAction->execute($request->all());
    }

    /**
     * Update credit card (POST /api/v1/credit-card/update)
     */
    public function update(Request $request): JsonResponse
    {
        // Implementation for updating credit card
        return response()->json(['message' => 'Update credit card endpoint not implemented yet']);
    }

    /**
     * Set preferred credit card (POST /api/v1/credit-card/set-preferred)
     */
    public function setPreferred(Request $request): JsonResponse
    {
        // Implementation for setting preferred credit card
        return response()->json(['message' => 'Set preferred credit card endpoint not implemented yet']);
    }

    /**
     * Delete credit card (POST /api/v1/credit-card/delete)
     */
    public function delete(Request $request): JsonResponse
    {
        // Implementation for deleting credit card
        return response()->json(['message' => 'Delete credit card endpoint not implemented yet']);
    }
}