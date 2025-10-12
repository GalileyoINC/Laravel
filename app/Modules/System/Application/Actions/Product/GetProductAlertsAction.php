<?php

declare(strict_types=1);

namespace App\Modules\Content\Application\Actions\Product;

use App\DTOs\Product\ProductAlertsRequestDTO;
use App\Services\Product\ProductServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetProductAlertsAction
{
    public function __construct(
        private readonly ProductServiceInterface $productService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = ProductAlertsRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid product alerts request'],
                    'message' => 'Invalid request parameters',
                ], 400);
            }

            $user = Auth::user();
            $alerts = $this->productService->getProductAlerts($dto, $user);

            return response()->json($alerts);

        } catch (Exception $e) {
            Log::error('GetProductAlertsAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
