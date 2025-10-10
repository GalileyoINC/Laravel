<?php

namespace App\Actions\CreditCard;

use App\DTOs\CreditCard\CreditCardListRequestDTO;
use App\Services\CreditCard\CreditCardServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetCreditCardListAction
{
    public function __construct(
        private CreditCardServiceInterface $creditCardService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = CreditCardListRequestDTO::fromArray($data);
            if (!$dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid credit card list request'],
                    'message' => 'Invalid request parameters'
                ], 400);
            }

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401
                ], 401);
            }

            $creditCards = $this->creditCardService->getCreditCardsForUser($dto, $user);

            return response()->json($creditCards->toArray());

        } catch (\Exception $e) {
            Log::error('GetCreditCardListAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
