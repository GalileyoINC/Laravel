<?php

declare(strict_types=1);

namespace App\Domain\Actions\Bundle;

use App\Domain\DTOs\Bundle\BundleUpdateRequestDTO;
use App\Domain\Services\Bundle\BundleServiceInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateBundleAction
{
    public function __construct(
        private readonly BundleServiceInterface $bundleService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): mixed
    {
        try {
            DB::beginTransaction();

            $dto = new BundleUpdateRequestDTO(
                id: $data['id'],
                name: $data['name'] ?? null,
                description: $data['description'] ?? null,
                price: $data['price'] ?? null,
                services: $data['services'] ?? null,
                is_active: $data['is_active'] ?? null
            );

            $bundle = $this->bundleService->update($dto);

            DB::commit();

            return $bundle;

        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
