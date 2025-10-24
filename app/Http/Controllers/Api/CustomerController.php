<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Customer\ChangePasswordAction;
use App\Domain\Actions\Customer\GetProfileAction;
use App\Domain\Actions\Customer\RemoveAvatarAction;
use App\Domain\Actions\Customer\RemoveHeaderAction;
use App\Domain\Actions\Customer\UpdatePrivacyAction;
use App\Domain\Actions\Customer\UpdateProfileAction;
use App\Domain\Services\Customer\CustomerServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

/**
 * Customer Controller
 * Handles user profile management, password changes, and account operations
 */
#[OA\Tag(name: 'Customer', description: 'Customer profile and account management operations')]
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
    #[OA\Post(
        path: '/api/v1/customer/get-profile',
        description: 'Get authenticated user profile',
        summary: 'Get profile',
        security: [['sanctum' => []]],
        tags: ['Customer'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profile retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Unauthorized'),
                    ]
                )
            ),
        ]
    )]
    public function getProfile(Request $request): JsonResponse
    {
        return $this->getProfileAction->execute($request->all());
    }

    /**
     * Update user profile (POST /api/v1/customer/update-profile)
     */
    #[OA\Post(
        path: '/api/v1/customer/update-profile',
        description: 'Update user profile information',
        summary: 'Update profile',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'John'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
                    new OA\Property(property: 'phone', type: 'string', example: '+1234567890'),
                    new OA\Property(property: 'bio', type: 'string', example: 'User biography'),
                ]
            )
        ),
        tags: ['Customer'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profile updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Profile updated successfully'),
                    ]
                )
            ),
        ]
    )]
    public function updateProfile(Request $request): JsonResponse
    {
        return $this->updateProfileAction->execute($request->all());
    }

    /**
     * Change password (POST /api/v1/customer/change-password)
     */
    #[OA\Post(
        path: '/api/v1/customer/change-password',
        description: 'Change user password',
        summary: 'Change password',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['current_password', 'new_password', 'new_password_confirmation'],
                properties: [
                    new OA\Property(property: 'current_password', type: 'string', format: 'password', example: 'currentpass123'),
                    new OA\Property(property: 'new_password', type: 'string', format: 'password', example: 'newpass123'),
                    new OA\Property(property: 'new_password_confirmation', type: 'string', format: 'password', example: 'newpass123'),
                ]
            )
        ),
        tags: ['Customer'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Password changed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Password changed successfully'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid current password',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Current password is incorrect'),
                    ]
                )
            ),
        ]
    )]
    public function changePassword(Request $request): JsonResponse
    {
        return $this->changePasswordAction->execute($request->all());
    }

    /**
     * Update privacy settings (POST /api/v1/customer/update-privacy)
     */
    #[OA\Post(
        path: '/api/v1/customer/update-privacy',
        description: 'Update user privacy settings',
        summary: 'Update privacy',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'profile_visibility', type: 'string', example: 'public'),
                    new OA\Property(property: 'email_visibility', type: 'string', example: 'private'),
                    new OA\Property(property: 'phone_visibility', type: 'string', example: 'private'),
                ]
            )
        ),
        tags: ['Customer'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Privacy settings updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Privacy settings updated'),
                    ]
                )
            ),
        ]
    )]
    public function updatePrivacy(Request $request): JsonResponse
    {
        return $this->updatePrivacyAction->execute($request->all());
    }

    /**
     * Remove avatar (POST /api/v1/customer/remove-avatar)
     */
    #[OA\Post(
        path: '/api/v1/customer/remove-avatar',
        description: 'Remove user avatar image',
        summary: 'Remove avatar',
        security: [['sanctum' => []]],
        tags: ['Customer'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Avatar removed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Avatar removed successfully'),
                    ]
                )
            ),
        ]
    )]
    public function removeAvatar(Request $request): JsonResponse
    {
        return $this->removeAvatarAction->execute($request->all());
    }

    /**
     * Remove header image (POST /api/v1/customer/remove-header)
     */
    #[OA\Post(
        path: '/api/v1/customer/remove-header',
        description: 'Remove user header image',
        summary: 'Remove header',
        security: [['sanctum' => []]],
        tags: ['Customer'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Header removed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Header removed successfully'),
                    ]
                )
            ),
        ]
    )]
    public function removeHeader(Request $request): JsonResponse
    {
        return $this->removeHeaderAction->execute($request->all());
    }

    /**
     * Logout user (POST /api/v1/customer/logout)
     */
    #[OA\Post(
        path: '/api/v1/customer/logout',
        description: 'Logout authenticated user',
        summary: 'Logout',
        security: [['sanctum' => []]],
        tags: ['Customer'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logged out successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Logged out successfully'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'User not authenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'User not authenticated'),
                        new OA\Property(property: 'code', type: 'integer', example: 401),
                    ]
                )
            ),
        ]
    )]
    public function logout(): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $result = $this->customerService->logout($user);

        return response()->json($result);
    }

    /**
     * Delete account (POST /api/v1/customer/delete)
     */
    #[OA\Post(
        path: '/api/v1/customer/delete',
        description: 'Delete user account permanently',
        summary: 'Delete account',
        security: [['sanctum' => []]],
        tags: ['Customer'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Account deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Account deleted successfully'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'User not authenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'User not authenticated'),
                        new OA\Property(property: 'code', type: 'integer', example: 401),
                    ]
                )
            ),
        ]
    )]
    public function delete(): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $result = $this->customerService->deleteAccount($user);

        return response()->json($result);
    }
}
