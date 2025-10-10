<?php

namespace App\Services\Device;

use App\DTOs\Device\DeviceUpdateRequestDTO;
use App\DTOs\Device\DeviceVerifyRequestDTO;
use App\DTOs\Device\PushSettingsRequestDTO;
use App\Models\User;

/**
 * Device service interface
 */
interface DeviceServiceInterface
{
    /**
     * Update device information
     *
     * @param DeviceUpdateRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function updateDevice(DeviceUpdateRequestDTO $dto, User $user);

    /**
     * Verify device UUID
     *
     * @param DeviceVerifyRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function verifyDevice(DeviceVerifyRequestDTO $dto, User $user);

    /**
     * Get push settings
     *
     * @param User $user
     * @return mixed
     */
    public function getPushSettings(User $user);

    /**
     * Set push settings
     *
     * @param PushSettingsRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function setPushSettings(PushSettingsRequestDTO $dto, User $user);
}
