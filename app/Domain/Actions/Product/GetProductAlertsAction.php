<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\ProductAlertsRequestDTO;
use App\Domain\Services\Product\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetProductAlertsAction
{
    public function __construct(
        private readonly ProductServiceInterface $productService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
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
    }
}
