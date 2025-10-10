<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Customer\GetProfileAction;
use App\Services\Customer\CustomerServiceInterface;
use App\DTOs\Customer\UpdateProfileRequestDTO;
use App\DTOs\Customer\ChangePasswordRequestDTO;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Customer Controller
 * Handles user profile management, password changes, and account operations
 */
class CustomerController extends Controller
{
    public function __construct(
        private GetProfileAction $getProfileAction,
        private CustomerServiceInterface $customerService
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
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401
                ], 401);
            }

            $dto = UpdateProfileRequestDTO::fromRequest($request);
            if (!$dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid profile update request'],
                    'message' => 'Invalid request parameters'
                ], 400);
            }

            $result = $this->customerService->updateProfile($dto, $user);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('CustomerController updateProfile error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }

    /**
     * Change password (POST /api/v1/customer/change-password)
     */
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401
                ], 401);
            }

            $dto = ChangePasswordRequestDTO::fromRequest($request);
            if (!$dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid password change request'],
                    'message' => 'Invalid request parameters'
                ], 400);
            }

            $result = $this->customerService->changePassword($dto, $user);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('CustomerController changePassword error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }

    /**
     * Logout user (POST /api/v1/customer/logout)
     */
    public function logout(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401
                ], 401);
            }

            $result = $this->customerService->logout($user);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('CustomerController logout error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
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
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401
                ], 401);
            }

            $result = $this->customerService->deleteAccount($user);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('CustomerController delete error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}