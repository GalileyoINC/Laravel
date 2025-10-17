<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Phone\UpdatePhoneSettingsAction;
use App\Domain\Actions\Phone\VerifyPhoneAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Refactored Phone Controller using DDD Actions
 * Handles phone verification and settings operations
 */
class PhoneController extends Controller
{
    public function __construct(
        private readonly VerifyPhoneAction $verifyPhoneAction,
        private readonly UpdatePhoneSettingsAction $updatePhoneSettingsAction
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
