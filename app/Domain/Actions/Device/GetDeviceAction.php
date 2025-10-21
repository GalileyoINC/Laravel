<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Domain\Services\Device\DeviceServiceInterface;
use Exception;

class GetDeviceAction
{
    public function __construct(
        private readonly DeviceServiceInterface $deviceService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): mixed
    {
        try {
            $device = $this->deviceService->getById($data['id']);

            return $device;

        } catch (Exception $e) {
            throw $e;
        }
    }
}
