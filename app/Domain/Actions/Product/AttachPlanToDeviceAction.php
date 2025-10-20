<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\AttachPlanToDeviceDTO;
use App\Models\Device\DevicePlan;
use Illuminate\Support\Facades\DB;

final class AttachPlanToDeviceAction
{
    public function execute(AttachPlanToDeviceDTO $dto): bool
    {
        return (bool) DB::transaction(function () use ($dto) {
            // Create or update device_plan row
            /** @var DevicePlan $plan */
            $plan = DevicePlan::query()->updateOrCreate(
                [
                    'id_device' => $dto->deviceId,
                    'id_plan' => $dto->planId,
                ],
                [
                    'price' => $dto->price,
                    'is_default' => (bool) ($dto->isDefault ?? false),
                ],
            );

            // Ensure only one default per device
            if ($plan->is_default) {
                DevicePlan::query()
                    ->where('id_device', $dto->deviceId)
                    ->where('id', '!=', $plan->id)
                    ->update(['is_default' => false]);
            }

            return $plan->exists;
        });
    }
}
