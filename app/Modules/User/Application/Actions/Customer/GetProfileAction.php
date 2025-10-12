<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Actions\Customer;

use App\DTOs\Customer\GetProfileRequestDTO;
use App\Http\Resources\CustomerResource;
use App\Services\Customer\CustomerServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetProfileAction
{
    public function __construct(
        private readonly CustomerServiceInterface $customerService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = GetProfileRequestDTO::fromArray($data);
            $user = Auth::user();

            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $profile = $this->customerService->getProfile($dto, $user);

            return response()->json(new CustomerResource($profile));

        } catch (Exception $e) {
            Log::error('GetProfileAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
