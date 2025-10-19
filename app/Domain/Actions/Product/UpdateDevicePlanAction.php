<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\DevicePlanUpdateDTO;
use App\Models\Device\DevicePlan;

final class UpdateDevicePlanAction
{
    public function execute(DevicePlanUpdateDTO $dto): DevicePlan
    {
        $plan = DevicePlan::findOrFail($dto->id);
        $plan->update([
            'name' => $dto->name,
            'description' => $dto->description,
            'price' => $dto->price,
            'is_active' => $dto->isActive,
        ]);

        return $plan;
    }
}
