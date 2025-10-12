<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Services\Customer;

use App\DTOs\Customer\ChangePasswordRequestDTO;
use App\DTOs\Customer\GetProfileRequestDTO;
use App\DTOs\Customer\UpdatePrivacyRequestDTO;
use App\DTOs\Customer\UpdateProfileRequestDTO;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
                'phone_profile' => $user->phone_profile ?? null,
                'country' => $user->country ?? null,
                'state' => $user->state ?? null,
                'city' => $user->city ?? null,
                'zip' => $user->zip ?? null,
                'about' => $user->about ?? null,
                'timezone' => $user->timezone ?? null,
                'image' => $user->image ? asset('storage/'.$user->image) : null,
                'header_image' => $user->header_image ?? null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'status' => $user->status ?? 1,
                'is_influencer' => $user->is_influencer ?? false,
                'bonus_point' => $user->bonus_point ?? 0,
                'is_valid_email' => $user->is_valid_email ?? false,
                'is_receive_subscribe' => $user->is_receive_subscribe ?? false,
                'is_receive_list' => $user->is_receive_list ?? false,
                'is_test' => $user->is_test ?? false,
                'is_assistant' => $user->is_assistant ?? false,
                'general_visibility' => $user->general_visibility ?? 0,
                'phone_visibility' => $user->phone_visibility ?? 1,
                'address_visibility' => $user->address_visibility ?? 0,
                'credit' => $user->credit ?? 0,
            ];

        } catch (Exception $e) {
            Log::error('CustomerService getProfile error: '.$e->getMessage());
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

            // Handle image file upload
            if ($dto->imageFile) {
                $imagePath = $dto->imageFile->store('profile-images', 'public');
                $user->update(['image' => $imagePath]);
            }

            // Update only provided fields
            $updateData = [];
            foreach ($dto->toArray() as $key => $value) {
                if ($value !== null) {
                    $updateData[$key] = $value;
                }
            }

            if (! empty($updateData)) {
                $user->update($updateData);
                $user->refresh();
            }

            DB::commit();

            $profileData = $this->getProfile(new GetProfileRequestDTO, $user);

            return [
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $profileData,
            ];

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CustomerService updateProfile error: '.$e->getMessage());
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
            if (! Hash::check($dto->currentPassword, $user->password)) {
                return [
                    'success' => false,
                    'message' => 'Current password is incorrect',
                    'errors' => ['current_password' => ['Current password is incorrect']],
                ];
            }

            // Update password
            $user->update([
                'password' => Hash::make($dto->newPassword),
            ]);

            return [
                'success' => true,
                'message' => 'Password changed successfully',
            ];

        } catch (Exception $e) {
            Log::error('CustomerService changePassword error: '.$e->getMessage());
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
                'message' => 'Logged out successfully',
            ];

        } catch (Exception $e) {
            Log::error('CustomerService logout error: '.$e->getMessage());
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
                'message' => 'Account deleted successfully',
            ];

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CustomerService deleteAccount error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy(UpdatePrivacyRequestDTO $dto, User $user): array
    {
        try {
            DB::beginTransaction();

            $user->update([
                'general_visibility' => $dto->generalVisibility,
                'phone_visibility' => $dto->phoneVisibility,
                'address_visibility' => $dto->addressVisibility,
                'updated_at' => now(),
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Privacy settings updated successfully',
                'general_visibility' => $dto->generalVisibility,
                'phone_visibility' => $dto->phoneVisibility,
                'address_visibility' => $dto->addressVisibility,
            ];

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CustomerService updatePrivacy error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Remove avatar
     */
    public function removeAvatar(User $user): array
    {
        try {
            DB::beginTransaction();

            $user->update([
                'image' => null,
                'updated_at' => now(),
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Avatar removed successfully',
                'image' => null,
            ];

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CustomerService removeAvatar error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Remove header image
     */
    public function removeHeader(User $user): array
    {
        try {
            DB::beginTransaction();

            $user->update([
                'header_image' => null,
                'updated_at' => now(),
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Header image removed successfully',
                'header_image' => null,
            ];

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CustomerService removeHeader error: '.$e->getMessage());
            throw $e;
        }
    }
}
