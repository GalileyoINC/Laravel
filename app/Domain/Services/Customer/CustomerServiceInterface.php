<?php

declare(strict_types=1);

namespace App\Domain\Services\Customer;

use App\Domain\DTOs\Customer\ChangePasswordRequestDTO;
use App\Domain\DTOs\Customer\GetProfileRequestDTO;
use App\Domain\DTOs\Customer\UpdatePrivacyRequestDTO;
use App\Domain\DTOs\Customer\UpdateProfileRequestDTO;
use App\Models\User\User;

/**
 * Customer service interface
 */
interface CustomerServiceInterface
{
    /**
     * Get user profile
     *
     * @return array<string, mixed>
     */
    public function getProfile(GetProfileRequestDTO $dto, User $user): array;

    /**
     * Update user profile
     *
     * @return array<string, mixed>
     */
    public function updateProfile(UpdateProfileRequestDTO $dto, User $user): array;

    /**
     * Change user password
     *
     * @return array<string, mixed>
     */
    public function changePassword(ChangePasswordRequestDTO $dto, User $user): array;

    /**
     * Logout user
     *
     * @return array<string, mixed>
     */
    public function logout(User $user): array;

    /**
     * Delete user account
     *
     * @return array<string, mixed>
     */
    public function deleteAccount(User $user): array;

    /**
     * Update privacy settings
     *
     * @return array<string, mixed>
     */
    public function updatePrivacy(UpdatePrivacyRequestDTO $dto, User $user): array;

    /**
     * Remove avatar
     *
     * @return array<string, mixed>
     */
    public function removeAvatar(User $user): array;

    /**
     * Remove header image
     *
     * @return array<string, mixed>
     */
    public function removeHeader(User $user): array;
}
