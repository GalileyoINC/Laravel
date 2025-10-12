<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Actions\Customer;

use App\DTOs\Customer\ChangePasswordRequestDTO;
use App\Http\Resources\SuccessResource;
use App\Services\Customer\CustomerServiceInterface;
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
