<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\DetachPlanFromDeviceDTO;

final class DetachPlanFromDeviceAction
{
    public function execute(DetachPlanFromDeviceDTO $dto): bool
    {
        // TODO: Implement actual detach logic using device/plan relations
        // e.g., Device::find($dto->deviceId)->devicePlans()->detach($dto->planId)
        return true;
    }
}
