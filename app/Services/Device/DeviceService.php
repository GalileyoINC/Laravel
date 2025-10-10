<?php

namespace App\Services\Device;

use App\DTOs\Device\DeviceUpdateRequestDTO;
use App\DTOs\Device\DeviceVerifyRequestDTO;
use App\DTOs\Device\PushSettingsRequestDTO;
use App\Models\User;
use App\Models\Device;
use Illuminate\Support\Facades\Log;

/**
 * Device service implementation
 */
class DeviceService implements DeviceServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function updateDevice(DeviceUpdateRequestDTO $dto, User $user)
    {
        try {
            $device = Device::where('id_user', $user->id)->first();
            
            if (!$device) {
                // Create new device if it doesn't exist
                $device = Device::create([
                    'id_user' => $user->id,
                    'os' => $dto->os,
                    'push_token' => $dto->pushToken,
                    'device_info' => json_encode($dto->info),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                // Update existing device
                $updateData = [];
                
                if ($dto->os !== null) $updateData['os'] = $dto->os;
                if ($dto->pushToken !== null) $updateData['push_token'] = $dto->pushToken;
                if ($dto->info !== null) $updateData['device_info'] = json_encode($dto->info);
                
                $updateData['updated_at'] = now();
                
                $device->update($updateData);
            }

            return $device;

        } catch (\Exception $e) {
            Log::error('DeviceService updateDevice error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function verifyDevice(DeviceVerifyRequestDTO $dto, User $user)
    {
        try {
            $device = Device::where('id_user', $user->id)
                ->where('uuid', $dto->uuid)
                ->first();

            if (!$device) {
                throw new \Exception('Device not found or UUID mismatch');
            }

            // Update device verification status
            $device->update([
                'is_verified' => true,
                'updated_at' => now()
            ]);

            return $device;

        } catch (\Exception $e) {
            Log::error('DeviceService verifyDevice error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPushSettings(User $user)
    {
        try {
            $device = Device::where('id_user', $user->id)->first();
            
            if (!$device) {
                return [
                    'push_turn_on' => false,
                    'is_send' => false,
                    'is_emergency_only' => false
                ];
            }

            return [
                'push_turn_on' => $device->push_turn_on ?? false,
                'is_send' => $device->is_send ?? false,
                'is_emergency_only' => $device->is_emergency_only ?? false
            ];

        } catch (\Exception $e) {
            Log::error('DeviceService getPushSettings error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPushSettings(PushSettingsRequestDTO $dto, User $user)
    {
        try {
            $device = Device::where('id_user', $user->id)->first();
            
            if (!$device) {
                throw new \Exception('Device not found');
            }

            $device->update([
                'push_turn_on' => $dto->pushTurnOn,
                'is_send' => $dto->isSend,
                'is_emergency_only' => $dto->isEmergencyOnly,
                'updated_at' => now()
            ]);

            return $device;

        } catch (\Exception $e) {
            Log::error('DeviceService setPushSettings error: ' . $e->getMessage());
            throw $e;
        }
    }
}
