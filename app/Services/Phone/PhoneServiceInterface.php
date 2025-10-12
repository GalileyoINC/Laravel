<?php

declare(strict_types=1);

namespace App\Services\Phone;

use App\DTOs\Phone\PhoneUpdateRequestDTO;
use App\DTOs\Phone\PhoneVerifyRequestDTO;
use App\DTOs\Phone\PhoneVerifyResponseDTO;
use App\Models\User\User\User;

/**
 * Phone service interface
 */
interface PhoneServiceInterface
{
    /**
     * Verify phone number
     */
    public function verifyPhone(PhoneVerifyRequestDTO $dto, User $user): PhoneVerifyResponseDTO;

    /**
     * Update phone settings
     *
     * @return mixed
     */
    public function updatePhoneSettings(PhoneUpdateRequestDTO $dto, User $user);
}
