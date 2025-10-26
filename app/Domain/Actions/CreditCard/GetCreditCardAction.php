<?php

declare(strict_types=1);

namespace App\Domain\Actions\CreditCard;

use App\Domain\Services\CreditCard\CreditCardServiceInterface;
use Illuminate\Http\JsonResponse;

class GetCreditCardAction
{
    public function __construct(
        private readonly CreditCardServiceInterface $creditCardService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $creditCard = $this->creditCardService->getById($data['id']);

        return response()->json([
            'status' => 'success',
            'data' => $creditCard,
        ]);
    }
}
