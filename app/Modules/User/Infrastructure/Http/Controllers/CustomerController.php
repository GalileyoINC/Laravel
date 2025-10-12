<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Customer Controller
 * Handles user profile management, password changes, and account operations
 */
class CustomerController extends Controller
{
    public function __construct(
        private readonly GetProfileAction $getProfileAction,
        private readonly UpdateProfileAction $updateProfileAction,
        private readonly ChangePasswordAction $changePasswordAction,
        private readonly UpdatePrivacyAction $updatePrivacyAction,
        private readonly RemoveAvatarAction $removeAvatarAction,
        private readonly RemoveHeaderAction $removeHeaderAction,
        private readonly CustomerServiceInterface $customerService
    ) {}

    /**
     * Get user profile (POST /api/v1/customer/get-profile)
     */
    public function getProfile(Request $request): JsonResponse
    {
        return $this->getProfileAction->execute($request->all());
    }

    /**
     * Update user profile (POST /api/v1/customer/update-profile)
     */
    public function updateProfile(Request $request): JsonResponse
    {
        return $this->updateProfileAction->execute($request->all());
    }

    /**
     * Change password (POST /api/v1/customer/change-password)
     */
    public function changePassword(Request $request): JsonResponse
    {
        return $this->changePasswordAction->execute($request->all());
    }

    /**
     * Update privacy settings (POST /api/v1/customer/update-privacy)
     */
    public function updatePrivacy(Request $request): JsonResponse
    {
        return $this->updatePrivacyAction->execute($request->all());
    }

    /**
     * Remove avatar (POST /api/v1/customer/remove-avatar)
     */
    public function removeAvatar(Request $request): JsonResponse
    {
        return $this->removeAvatarAction->execute($request->all());
    }

    /**
     * Remove header image (POST /api/v1/customer/remove-header)
     */
    public function removeHeader(Request $request): JsonResponse
    {
        return $this->removeHeaderAction->execute($request->all());
    }

    /**
     * Logout user (POST /api/v1/customer/logout)
     */
    public function logout(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $result = $this->customerService->logout($user);

            return response()->json($result);

        } catch (Exception $e) {
            Log::error('CustomerController logout error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }

    /**
     * Delete account (POST /api/v1/customer/delete)
     */
    public function delete(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $result = $this->customerService->deleteAccount($user);

            return response()->json($result);

        } catch (Exception $e) {
            Log::error('CustomerController delete error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
