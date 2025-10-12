<?php

declare(strict_types=1);

namespace App\Actions\Subscription;

use App\DTOs\Subscription\SubscriptionRequestDTO;
use App\Services\Subscription\SubscriptionServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SetSubscriptionAction
{
    public function __construct(
        private readonly SubscriptionServiceInterface $subscriptionService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
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

        } catch (Exception $e) {
            Log::error('SetSubscriptionAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
