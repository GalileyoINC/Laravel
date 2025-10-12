<?php

declare(strict_types=1);

namespace App\Modules\Content\Application\Actions\Product;

use App\DTOs\Product\ApplePurchaseRequestDTO;
use App\Services\Product\ProductServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProcessApplePurchaseAction
{
    public function __construct(
        private readonly ProductServiceInterface $productService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = ApplePurchaseRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid Apple purchase request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $purchase = $this->productService->processApplePurchase($dto, $user);

            return response()->json($purchase);

        } catch (Exception $e) {
            Log::error('ProcessApplePurchaseAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
