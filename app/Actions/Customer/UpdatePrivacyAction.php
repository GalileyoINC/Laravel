<?php

namespace App\Actions\Customer;

use App\DTOs\Customer\UpdatePrivacyRequestDTO;
use App\Services\Customer\CustomerServiceInterface;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdatePrivacyAction
{
    public function __construct(
        private CustomerServiceInterface $customerService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = UpdatePrivacyRequestDTO::fromArray($data);
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401
                ], 401);
            }

            $result = $this->customerService->updatePrivacy($dto, $user);

            return response()->json(new SuccessResource($result));

        } catch (\Exception $e) {
            Log::error('UpdatePrivacyAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
