<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\DeviceUpdateDTO;
use App\Models\Device\Device;

final class UpdateDeviceAction
{
    public function execute(DeviceUpdateDTO $dto): Device
    {
        $device = Device::findOrFail($dto->id);
        $device->update([
            'name' => $dto->name,
            'description' => $dto->description,
            'price' => $dto->price,
            'special_price' => $dto->specialPrice,
            'is_special_price' => $dto->isSpecialPrice,
            'is_active' => $dto->isActive,
        ]);

        return $device;
    }
}
