<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\AttachPlanToDeviceDTO;

final class AttachPlanToDeviceAction
{
    public function execute(AttachPlanToDeviceDTO $dto): bool
    {
        // TODO: Implement actual attach logic using device/plan relations
        // e.g., Device::find($dto->deviceId)->devicePlans()->attach($dto->planId, [...])
        return true;
    }
}
