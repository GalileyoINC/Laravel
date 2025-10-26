<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\ApplePurchaseRequestDTO;
use App\Domain\Services\Product\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProcessApplePurchaseAction
{
    public function __construct(
        private readonly ProductServiceInterface $productService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
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
    }
}
