<?php

declare(strict_types=1);

namespace App\Domain\Services\Bundle;

use App\Domain\DTOs\Bundle\BundleCreateRequestDTO;
use App\Domain\DTOs\Bundle\BundleDeviceDataRequestDTO;
use App\Domain\DTOs\Bundle\BundleUpdateRequestDTO;
use App\Models\Finance\Bundle;
use App\Models\Finance\Service;

class BundleService implements BundleServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getList(int $page, int $limit, ?string $search, ?int $status): array
    {
        $query = Bundle::query();

        if ($search) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        if ($status !== null) {
            $query->where('is_active', $status);
        }

        $bundles = $query->paginate($limit, ['*'], 'page', $page);

        return [
            'data' => $bundles->items(),
            'pagination' => [
                'current_page' => $bundles->currentPage(),
                'last_page' => $bundles->lastPage(),
                'per_page' => $bundles->perPage(),
                'total' => $bundles->total(),
            ],
        ];
    }

    public function create(BundleCreateRequestDTO $dto): Bundle
    {
        $bundle = Bundle::create([
            'name' => $dto->name,
            'description' => $dto->description,
            'price' => $dto->price,
            'is_active' => $dto->is_active,
        ]);

        if (! empty($dto->services)) {
            $bundle->services()->attach($dto->services);
        }

        return $bundle->load('services');
    }

    public function update(BundleUpdateRequestDTO $dto): Bundle
    {
        $bundle = Bundle::findOrFail($dto->id);

        $updateData = [];
        if ($dto->name !== null) {
            $updateData['name'] = $dto->name;
        }
        if ($dto->description !== null) {
            $updateData['description'] = $dto->description;
        }
        if ($dto->price !== null) {
            $updateData['price'] = $dto->price;
        }
        if ($dto->is_active !== null) {
            $updateData['is_active'] = $dto->is_active;
        }

        if (! empty($updateData)) {
            $bundle->update($updateData);
        }

        if ($dto->services !== null) {
            $bundle->services()->sync($dto->services);
        }

        return $bundle->load('services');
    }

    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function getDeviceData(BundleDeviceDataRequestDTO $dto): array
    {
        $device = Service::findOrFail($dto->idDevice);

        $plans = Service::query()
            ->join('bundle_items', 'services.id', '=', 'bundle_items.id_service')
            ->where('bundle_items.id_device', $dto->idDevice)
            ->where('services.is_active', 1)
            ->select('services.*')
            ->get();

        return [
            'price' => $device->price,
            'plans' => $plans->toArray(),
        ];
    }
}
