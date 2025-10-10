<?php

namespace App\Services\Customer;

use App\DTOs\Customer\GetProfileRequestDTO;
use App\DTOs\Customer\UpdateProfileRequestDTO;
use App\DTOs\Customer\ChangePasswordRequestDTO;
use App\Models\User;

/**
 * Customer service interface
 */
interface CustomerServiceInterface
{
    /**
     * Get user profile
     *
     * @param GetProfileRequestDTO $dto
     * @param User $user
     * @return array
     */
    public function getProfile(GetProfileRequestDTO $dto, User $user): array;

    /**
     * Update user profile
     *
     * @param UpdateProfileRequestDTO $dto
     * @param User $user
     * @return array
     */
    public function updateProfile(UpdateProfileRequestDTO $dto, User $user): array;

    /**
     * Change user password
     *
     * @param ChangePasswordRequestDTO $dto
     * @param User $user
     * @return array
     */
    public function changePassword(ChangePasswordRequestDTO $dto, User $user): array;

    /**
     * Logout user
     *
     * @param User $user
     * @return array
     */
    public function logout(User $user): array;

    /**
     * Delete user account
     *
     * @param User $user
     * @return array
     */
    public function deleteAccount(User $user): array;
}
