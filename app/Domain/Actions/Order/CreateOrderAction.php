<?php

declare(strict_types=1);

namespace App\Domain\Actions\Order;

use App\Domain\DTOs\Order\CreateOrderDTO;
use App\Domain\DTOs\Order\OrderResponseDTO;
use App\Domain\Services\Order\OrderServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateOrderAction
{
    public function __construct(
        private readonly OrderServiceInterface $orderService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        try {
            $dto = CreateOrderDTO::fromArray($data);
            if (! $dto->validate()) {
                return response()->json([
                    'errors' => ['Invalid order creation request'],
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

            $order = $this->orderService->createOrder($dto, $user);
            $responseDto = OrderResponseDTO::fromModel($order);

            return response()->json($responseDto->toArray());

        } catch (Exception $e) {
            Log::error('CreateOrderAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
