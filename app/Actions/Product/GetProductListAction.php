<?php

namespace App\Actions\Product;

use App\DTOs\Product\ProductListRequestDTO;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetProductListAction
{
    public function __construct(
        private ProductServiceInterface $productService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = ProductListRequestDTO::fromArray($data);
            if (!$dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid product list request'],
                    'message' => 'Invalid request parameters'
                ], 400);
            }

            $user = Auth::user();
            $products = $this->productService->getProductList($dto, $user);

            return response()->json($products);

        } catch (\Exception $e) {
            Log::error('GetProductListAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
