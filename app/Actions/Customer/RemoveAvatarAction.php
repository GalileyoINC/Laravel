<?php

declare(strict_types=1);

namespace App\Actions\Customer;

use App\Http\Resources\SuccessResource;
use App\Services\Customer\CustomerServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RemoveAvatarAction
{
    public function __construct(
        private readonly CustomerServiceInterface $customerService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $user = Auth::user();

            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $result = $this->customerService->removeAvatar($user);

            return response()->json(new SuccessResource($result));

        } catch (Exception $e) {
            Log::error('RemoveAvatarAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
