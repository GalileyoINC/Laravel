<?php

declare(strict_types=1);

namespace App\Domain\Actions\Customer;

use App\Domain\Services\Customer\CustomerServiceInterface;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RemoveAvatarAction
{
    public function __construct(
        private readonly CustomerServiceInterface $customerService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $result = $this->customerService->removeAvatar($user);

        return response()->json(new SuccessResource($result));
    }
}
