<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\DeviceCreateDTO;
use App\Models\Device\Device;

final class CreateDeviceAction
{
    public function execute(DeviceCreateDTO $dto): Device
    {
        return Device::create([
            'name' => $dto->name,
            'description' => $dto->description,
            'price' => $dto->price,
            'special_price' => $dto->specialPrice,
            'is_special_price' => $dto->isSpecialPrice,
            'is_active' => $dto->isActive,
        ]);
    }
}
