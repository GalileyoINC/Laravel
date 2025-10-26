<?php

declare(strict_types=1);

namespace App\Domain\Actions\Subscription;

use App\Domain\DTOs\Subscription\SubscriptionRequestDTO;
use App\Domain\Services\Subscription\SubscriptionServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SetSubscriptionAction
{
    public function __construct(
        private readonly SubscriptionServiceInterface $subscriptionService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $dto = SubscriptionRequestDTO::fromArray($data);
        if (! $dto->validate()) {
            return response()->json([
                'errors' => ['Invalid subscription request'],
                'message' => 'Invalid request parameters',
            ], 400);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error' => 'User not authenticated',
                'code' => 401,
            ], 401);
        }

        $result = $this->subscriptionService->setSubscription($dto, $user);

        return response()->json($result);
    }
}
