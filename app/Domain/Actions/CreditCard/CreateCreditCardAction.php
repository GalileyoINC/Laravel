<?php

declare(strict_types=1);

namespace App\Domain\Actions\CreditCard;

use App\Domain\DTOs\CreditCard\CreditCardCreateRequestDTO;
use App\Domain\Services\CreditCard\CreditCardServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CreateCreditCardAction
{
    public function __construct(
        private readonly CreditCardServiceInterface $creditCardService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
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

        $creditCard = $this->creditCardService->getById(1); // Placeholder - implement actual creation

        return response()->json($creditCard->toArray());
    }
}
