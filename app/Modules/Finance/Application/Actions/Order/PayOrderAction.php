<?php

declare(strict_types=1);

namespace App\Modules\Finance\Application\Actions\Order;

use App\DTOs\Order\OrderResponseDTO;
use App\DTOs\Order\PayOrderDTO;
use App\Services\Order\OrderServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PayOrderAction
{
    public function __construct(
        private readonly OrderServiceInterface $orderService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = PayOrderDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid payment request'],
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

            $order = $this->orderService->payOrder($dto, $user);
            $responseDto = OrderResponseDTO::fromModel($order);

            return response()->json($responseDto->toArray());

        } catch (Exception $e) {
            Log::error('PayOrderAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
