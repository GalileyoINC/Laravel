<?php

declare(strict_types=1);

namespace App\Actions\CreditCard;

use App\Services\CreditCard\CreditCardServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetCreditCardAction
{
    public function __construct(
        private readonly CreditCardServiceInterface $creditCardService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $creditCard = $this->creditCardService->getById($data['id']);

            return response()->json([
                'status' => 'success',
                'data' => $creditCard,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get credit card: '.$e->getMessage(),
            ], 500);
        }
    }
}
