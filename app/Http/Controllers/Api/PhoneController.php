<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Phone\UpdatePhoneSettingsAction;
use App\Domain\Actions\Phone\VerifyPhoneAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

/**
 * Refactored Phone Controller using DDD Actions
 * Handles phone verification and settings operations
 */
#[OA\Tag(name: 'Phone', description: 'Phone verification and settings operations')]
class PhoneController extends Controller
{
    public function __construct(
        private readonly VerifyPhoneAction $verifyPhoneAction,
        private readonly UpdatePhoneSettingsAction $updatePhoneSettingsAction
    ) {}

    /**
     * Verify phone (POST /api/v1/phone/verify)
     */
    #[OA\Post(
        path: '/api/v1/phone/verify',
        description: 'Verify phone number with SMS code',
        summary: 'Verify phone',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['phone_number', 'verification_code'],
                properties: [
                    new OA\Property(property: 'phone_number', type: 'string', example: '+1234567890'),
                    new OA\Property(property: 'verification_code', type: 'string', example: '123456'),
                ]
            )
        ),
        tags: ['Phone'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Phone verified successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Phone verified successfully'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid verification code',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Invalid verification code'),
                    ]
                )
            ),
        ]
    )]
    public function verify(Request $request): JsonResponse
    {
        return $this->verifyPhoneAction->execute($request->all());
    }

    /**
     * Update phone settings (POST /api/v1/phone/set)
     */
    #[OA\Post(
        path: '/api/v1/phone/set',
        description: 'Update phone settings and preferences',
        summary: 'Update phone settings',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'phone_number', type: 'string', example: '+1234567890'),
                    new OA\Property(property: 'notifications_enabled', type: 'boolean', example: true),
                    new OA\Property(property: 'sms_enabled', type: 'boolean', example: true),
                    new OA\Property(property: 'call_enabled', type: 'boolean', example: false),
                ]
            )
        ),
        tags: ['Phone'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Phone settings updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Phone settings updated successfully'),
                    ]
                )
            ),
        ]
    )]
    public function set(Request $request): JsonResponse
    {
        return $this->updatePhoneSettingsAction->execute($request->all());
    }
}
