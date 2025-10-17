<?php

declare(strict_types=1);

namespace App\Domain\Services\Phone;

use App\Domain\DTOs\Phone\PhoneUpdateRequestDTO;
use App\Domain\DTOs\Phone\PhoneVerifyRequestDTO;
use App\Domain\DTOs\Phone\PhoneVerifyResponseDTO;
use App\Models\User\User;

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
