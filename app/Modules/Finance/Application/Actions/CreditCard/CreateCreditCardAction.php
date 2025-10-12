<?php

declare(strict_types=1);

namespace App\Actions\CreditCard;

use App\DTOs\CreditCard\CreditCardCreateRequestDTO;
use App\Services\CreditCard\CreditCardServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateCreditCardAction
{
    public function __construct(
        private readonly CreditCardServiceInterface $creditCardService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = CreditCardCreateRequestDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid credit card create request'],
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

            $creditCard = $this->creditCardService->createCreditCard($dto, $user);

            return response()->json($creditCard->toArray());

        } catch (Exception $e) {
            Log::error('CreateCreditCardAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
