<?php

declare(strict_types=1);

namespace App\Domain\Actions\Customer;

use App\Domain\DTOs\Customer\ChangePasswordRequestDTO;
use App\Domain\Services\Customer\CustomerServiceInterface;
use App\Http\Resources\SuccessResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChangePasswordAction
{
    public function __construct(
        private readonly CustomerServiceInterface $customerService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = ChangePasswordRequestDTO::fromArray($data);
            $user = Auth::user();

            if (! $user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401,
                ], 401);
            }

            $result = $this->customerService->changePassword($dto, $user);

            return response()->json(new SuccessResource($result));

        } catch (Exception $e) {
            Log::error('ChangePasswordAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
