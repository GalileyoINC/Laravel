<?php

declare(strict_types=1);

namespace App\Services\Customer;

use App\DTOs\Customer\ChangePasswordRequestDTO;
use App\DTOs\Customer\GetProfileRequestDTO;
use App\DTOs\Customer\UpdatePrivacyRequestDTO;
use App\DTOs\Customer\UpdateProfileRequestDTO;
use App\Models\User\User;

/**
 * Customer service interface
 */
interface CustomerServiceInterface
{
    /**
     * Get user profile
     */
    public function getProfile(GetProfileRequestDTO $dto, User $user): array;

    /**
     * Update user profile
     */
    public function updateProfile(UpdateProfileRequestDTO $dto, User $user): array;

    /**
     * Change user password
     */
    public function changePassword(ChangePasswordRequestDTO $dto, User $user): array;

    /**
     * Logout user
     */
    public function logout(User $user): array;

    /**
     * Delete user account
     */
    public function deleteAccount(User $user): array;

    /**
     * Update privacy settings
     */
    public function updatePrivacy(UpdatePrivacyRequestDTO $dto, User $user): array;

    /**
     * Remove avatar
     */
    public function removeAvatar(User $user): array;

    /**
     * Remove header image
     */
    public function removeHeader(User $user): array;
}
