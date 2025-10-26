<?php

declare(strict_types=1);

namespace App\Domain\Actions\Customer;

use App\Domain\DTOs\Customer\GetProfileRequestDTO;
use App\Domain\Services\Customer\CustomerServiceInterface;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetProfileAction
{
    public function __construct(
        private readonly CustomerServiceInterface $customerService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
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
    }
}
