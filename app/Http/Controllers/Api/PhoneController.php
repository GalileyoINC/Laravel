<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Phone\VerifyPhoneAction;
use App\Actions\Phone\UpdatePhoneSettingsAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Refactored Phone Controller using DDD Actions
 * Handles phone verification and settings operations
 */
class PhoneController extends Controller
{
    public function __construct(
        private VerifyPhoneAction $verifyPhoneAction,
        private UpdatePhoneSettingsAction $updatePhoneSettingsAction
    ) {}

    /**
     * Verify phone (POST /api/v1/phone/verify)
     */
    public function verify(Request $request): JsonResponse
    {
        return $this->verifyPhoneAction->execute($request->all());
    }

    /**
     * Update phone settings (POST /api/v1/phone/set)
     */
    public function set(Request $request): JsonResponse
    {
        return $this->updatePhoneSettingsAction->execute($request->all());
    }
}