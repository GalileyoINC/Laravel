<?php

declare(strict_types=1);

namespace App\Domain\Actions\Bundle;

use App\Domain\DTOs\Bundle\BundleCreateRequestDTO;
use App\Domain\Services\Bundle\BundleServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CreateBundleAction
{
    public function __construct(
        private readonly BundleServiceInterface $bundleService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $dto = new BundleCreateRequestDTO(
                name: $data['name'],
                description: $data['description'] ?? null,
                price: $data['price'],
                services: $data['services'] ?? [],
                is_active: $data['is_active'] ?? true
            );

            $bundle = $this->bundleService->create($dto);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Bundle created successfully',
                'data' => $bundle,
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create bundle: '.$e->getMessage(),
            ], 500);
        }
    }
}
