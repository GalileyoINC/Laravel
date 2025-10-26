<?php

declare(strict_types=1);

namespace App\Domain\Actions\Payment;

use App\Domain\Services\Payment\PaymentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * DeleteCreditCardAction
 * Action for deleting a credit card
 */
class DeleteCreditCardAction
{
    public function __construct(
        private readonly PaymentServiceInterface $paymentService
    ) {}

    public function execute(int $cardId): JsonResponse
    {
        $user = Auth::user();

        $this->paymentService->deleteCreditCard($user, $cardId);

        return response()->json([
            'status' => 'success',
            'message' => 'Credit card deleted successfully',
        ]);
    }
}
