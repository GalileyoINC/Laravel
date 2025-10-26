<?php

declare(strict_types=1);

namespace App\Domain\Actions\Customer;

use App\Domain\DTOs\Customer\UpdatePrivacyRequestDTO;
use App\Domain\Services\Customer\CustomerServiceInterface;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdatePrivacyAction
{
    public function __construct(
        private readonly CustomerServiceInterface $customerService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $dto = UpdatePrivacyRequestDTO::fromArray($data);
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $result = $this->customerService->updatePrivacy($dto, $user);

        return response()->json(new SuccessResource($result));
    }
}
