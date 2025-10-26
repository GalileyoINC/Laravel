<?php

declare(strict_types=1);

namespace App\Domain\Actions\Bundle;

use App\Domain\DTOs\Bundle\BundleCreateRequestDTO;
use App\Domain\Services\Bundle\BundleServiceInterface;
use Illuminate\Support\Facades\DB;

class CreateBundleAction
{
    public function __construct(
        private readonly BundleServiceInterface $bundleService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): mixed
    {
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

        return $bundle;
    }
}
