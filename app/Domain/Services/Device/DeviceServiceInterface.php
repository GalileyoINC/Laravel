<?php

declare(strict_types=1);

namespace App\Domain\Services\Device;

use App\Domain\DTOs\Device\DeviceListRequestDTO;
use App\Domain\DTOs\Device\DevicePushRequestDTO;
use App\Models\Device\Device;

interface DeviceServiceInterface
{
    public function getList(DeviceListRequestDTO $dto): array;

    public function getById(int $id): Device;

    public function delete(int $id): void;

    public function sendPushNotification(DevicePushRequestDTO $dto): array;
}
