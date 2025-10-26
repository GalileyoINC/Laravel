<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\ProductAlertMapRequestDTO;
use App\Domain\Services\Product\ProductAlertMapServiceInterface;
use App\Http\Resources\ProductAlertMapResource;
use Illuminate\Http\JsonResponse;

class GetProductAlertsWithMapAction
{
    public function __construct(
        private readonly ProductAlertMapServiceInterface $productAlertMapService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $dto = ProductAlertMapRequestDTO::fromArray($data);

        $alerts = $this->productAlertMapService->getAlertsWithMapData($dto);

        return response()->json([
            'status' => 'success',
            'data' => ProductAlertMapResource::collection($alerts),
            'meta' => [
                'total' => $alerts->count(),
                'limit' => $dto->limit,
                'offset' => $dto->offset,
            ],
        ]);
    }
}
