<?php

namespace App\Actions\Customer;

use App\DTOs\Customer\GetProfileRequestDTO;
use App\Services\Customer\CustomerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetProfileAction
{
    public function __construct(
        private CustomerServiceInterface $customerService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = GetProfileRequestDTO::fromArray($data);
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401
                ], 401);
            }

            $profile = $this->customerService->getProfile($dto, $user);

            return response()->json($profile);

        } catch (\Exception $e) {
            Log::error('GetProfileAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
