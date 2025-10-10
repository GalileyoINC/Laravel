<?php

namespace App\Actions\Order;

use App\DTOs\Order\PayOrderDTO;
use App\DTOs\Order\OrderResponseDTO;
use App\Services\Order\OrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PayOrderAction
{
    public function __construct(
        private OrderServiceInterface $orderService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = PayOrderDTO::fromArray($data);
            if (!$dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid payment request'],
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

            $order = $this->orderService->payOrder($dto, $user);
            $responseDto = OrderResponseDTO::fromModel($order);

            return response()->json($responseDto->toArray());

        } catch (\Exception $e) {
            Log::error('PayOrderAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
