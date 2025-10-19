<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\DevicePlanCreateDTO;
use App\Models\Device\DevicePlan;

final class CreateDevicePlanAction
{
    public function execute(DevicePlanCreateDTO $dto): DevicePlan
    {
        return DevicePlan::create([
            'name' => $dto->name,
            'description' => $dto->description,
            'price' => $dto->price,
            'is_active' => $dto->isActive,
        ]);
    }
}
