<?php

declare(strict_types=1);

namespace App\Services\Phone;

use App\DTOs\Phone\PhoneUpdateRequestDTO;
use App\DTOs\Phone\PhoneVerifyRequestDTO;
use App\DTOs\Phone\PhoneVerifyResponseDTO;
use App\Models\Device\Device\PhoneNumber;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Phone service implementation
 */
class PhoneService implements PhoneServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function verifyPhone(PhoneVerifyRequestDTO $dto, User $user): PhoneVerifyResponseDTO
    {
        try {
            $phone = PhoneNumber::where('id', $dto->id)
                ->where('id_user', $user->id)
                ->first();

            if (! $phone) {
                throw new Exception('Phone number not found or unauthorized');
            }

            // Generate verification code (simplified)
            $verificationCode = random_int(100000, 999999);

            // Update phone with verification code
            $phone->update([
                'verification_code' => $verificationCode,
                'is_verified' => false,
                'updated_at' => now(),
            ]);

            // In real application, send SMS here
            // $this->sendSMS($phone->phone, $verificationCode);

            return PhoneVerifyResponseDTO::fromData(
                'Verification code sent to '.$phone->phone,
                'sent'
            );

        } catch (Exception $e) {
            Log::error('PhoneService verifyPhone error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updatePhoneSettings(PhoneUpdateRequestDTO $dto, User $user)
    {
        try {
            $phone = PhoneNumber::where('id', $dto->id)
                ->where('id_user', $user->id)
                ->first();

            if (! $phone) {
                throw new Exception('Phone number not found or unauthorized');
            }

            $phone->update([
                'is_send' => $dto->isSend,
                'is_emergency_only' => $dto->isEmergencyOnly,
                'updated_at' => now(),
            ]);

            return $phone;

        } catch (Exception $e) {
            Log::error('PhoneService updatePhoneSettings error: '.$e->getMessage());
            throw $e;
        }
    }
}
