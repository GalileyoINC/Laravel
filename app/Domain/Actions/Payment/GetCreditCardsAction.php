<?php

declare(strict_types=1);

namespace App\Domain\Actions\Payment;

use App\Domain\DTOs\Payment\PaymentListRequestDTO;
use App\Domain\Services\Payment\PaymentServiceInterface;
use App\Http\Resources\Payment\CreditCardListResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * GetCreditCardsAction
 * Action for getting user's credit cards
 */
class GetCreditCardsAction
{
    public function __construct(
        private readonly PaymentServiceInterface $paymentService
    ) {}

    public function execute(PaymentListRequestDTO $dto): JsonResponse
    {
        $user = Auth::user();
        
        $result = $this->paymentService->getCreditCards(
            $user, 
            $dto->limit, 
            $dto->page
        );
        
        return response()->json([
            'status' => 'success',
            'data' => new CreditCardListResource($result),
        ]);
    }
}
