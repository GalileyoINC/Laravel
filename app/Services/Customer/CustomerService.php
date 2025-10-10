<?php

namespace App\Services\Customer;

use App\DTOs\Customer\GetProfileRequestDTO;
use App\DTOs\Customer\UpdateProfileRequestDTO;
use App\DTOs\Customer\ChangePasswordRequestDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Customer service implementation
 */
class CustomerService implements CustomerServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getProfile(GetProfileRequestDTO $dto, User $user): array
    {
        try {
            // Return user profile data
            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone ?? null,
                'country' => $user->country ?? null,
                'state' => $user->state ?? null,
                'city' => $user->city ?? null,
                'zip_code' => $user->zip_code ?? null,
                'address' => $user->address ?? null,
                'bio' => $user->bio ?? null,
                'website' => $user->website ?? null,
                'timezone' => $user->timezone ?? null,
                'avatar' => $user->avatar ?? null,
                'header_image' => $user->header_image ?? null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'is_active' => $user->is_active ?? true,
                'email_verified_at' => $user->email_verified_at
            ];

        } catch (\Exception $e) {
            Log::error('CustomerService getProfile error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateProfile(UpdateProfileRequestDTO $dto, User $user): array
    {
        try {
            DB::beginTransaction();

            // Update only provided fields
            $updateData = [];
            foreach ($dto->toArray() as $key => $value) {
                if ($value !== null) {
                    $updateData[$key] = $value;
                }
            }

            if (!empty($updateData)) {
                $user->update($updateData);
                $user->refresh();
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => $this->getProfile(new GetProfileRequestDTO(), $user)
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CustomerService updateProfile error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function changePassword(ChangePasswordRequestDTO $dto, User $user): array
    {
        try {
            // Verify current password
            if (!Hash::check($dto->currentPassword, $user->password)) {
                return [
                    'success' => false,
                    'message' => 'Current password is incorrect',
                    'errors' => ['current_password' => ['Current password is incorrect']]
                ];
            }

            // Update password
            $user->update([
                'password' => Hash::make($dto->newPassword)
            ]);

            return [
                'success' => true,
                'message' => 'Password changed successfully'
            ];

        } catch (\Exception $e) {
            Log::error('CustomerService changePassword error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function logout(User $user): array
    {
        try {
            // In Laravel, logout is typically handled by the auth system
            // This method can be used for additional cleanup if needed
            
            // Revoke all tokens for the user (if using Sanctum)
            $user->tokens()->delete();

            return [
                'success' => true,
                'message' => 'Logged out successfully'
            ];

        } catch (\Exception $e) {
            Log::error('CustomerService logout error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteAccount(User $user): array
    {
        try {
            DB::beginTransaction();

            // Soft delete the user account
            $user->delete();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Account deleted successfully'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CustomerService deleteAccount error: ' . $e->getMessage());
            throw $e;
        }
    }
}
