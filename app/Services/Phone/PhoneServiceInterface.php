<?php

namespace App\Services\Phone;

use App\DTOs\Phone\PhoneVerifyRequestDTO;
use App\DTOs\Phone\PhoneUpdateRequestDTO;
use App\DTOs\Phone\PhoneVerifyResponseDTO;
use App\Models\User;

/**
 * Phone service interface
 */
interface PhoneServiceInterface
{
    /**
     * Verify phone number
     *
     * @param PhoneVerifyRequestDTO $dto
     * @param User $user
     * @return PhoneVerifyResponseDTO
     */
    public function verifyPhone(PhoneVerifyRequestDTO $dto, User $user): PhoneVerifyResponseDTO;

    /**
     * Update phone settings
     *
     * @param PhoneUpdateRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function updatePhoneSettings(PhoneUpdateRequestDTO $dto, User $user);
}
