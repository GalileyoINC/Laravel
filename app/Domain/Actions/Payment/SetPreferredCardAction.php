<?php

declare(strict_types=1);

namespace App\Domain\Actions\Payment;

use App\Domain\Services\Payment\PaymentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * SetPreferredCardAction
 * Action for setting a credit card as preferred
 */
class SetPreferredCardAction
{
    public function __construct(
        private readonly PaymentServiceInterface $paymentService
    ) {}

    public function execute(int $cardId): JsonResponse
    {
        $user = Auth::user();
        
        $success = $this->paymentService->setPreferredCard($user, $cardId);
        
        if (!$success) {
            return response()->json([
                'status' => 'error',
                'message' => 'Card is not active',
            ], 400);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Preferred card set successfully',
        ]);
    }
}
