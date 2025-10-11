<?php

namespace App\Actions\Customer;

use App\DTOs\Customer\UpdateProfileRequestDTO;
use App\Services\Customer\CustomerServiceInterface;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateProfileAction
{
    public function __construct(
        private CustomerServiceInterface $customerService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = UpdateProfileRequestDTO::fromArray($data);
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401
                ], 401);
            }

            $result = $this->customerService->updateProfile($dto, $user);

            return response()->json(new CustomerResource($result));

        } catch (\Exception $e) {
            Log::error('UpdateProfileAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
