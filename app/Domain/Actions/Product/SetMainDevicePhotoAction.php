<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\DevicePhotoSetMainDTO;

final class SetMainDevicePhotoAction
{
    public function execute(DevicePhotoSetMainDTO $dto): bool
    {
        // TODO: Implement actual set-main logic for device photo by ID (db flags)
        return true;
    }
}
