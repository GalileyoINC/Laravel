<?php

declare(strict_types=1);

namespace App\Domain\Actions\Payment;

use App\Domain\DTOs\Payment\PaymentDetailsDTO;
use App\Domain\Services\Payment\PaymentServiceInterface;
use App\Http\Resources\Payment\CreditCardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * CreateCreditCardAction
 * Action for creating a new credit card
 */
class CreateCreditCardAction
{
    public function __construct(
        private readonly PaymentServiceInterface $paymentService
    ) {}

    public function execute(PaymentDetailsDTO $dto): JsonResponse
    {
        $user = Auth::user();
        
        $card = $this->paymentService->createCreditCard($user, $dto);
        
        return response()->json([
            'status' => 'success',
            'data' => new CreditCardResource($card),
            'message' => 'Credit card created successfully',
        ]);
    }
}
