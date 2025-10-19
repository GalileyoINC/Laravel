<?php

declare(strict_types=1);

namespace App\Domain\Actions\Product;

use App\Domain\DTOs\Product\DevicePhotoDeleteDTO;

final class DeleteDevicePhotoAction
{
    public function execute(DevicePhotoDeleteDTO $dto): bool
    {
        // TODO: Implement actual delete logic for photo by ID (db + storage)
        return true;
    }
}
