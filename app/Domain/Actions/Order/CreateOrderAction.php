<?php

declare(strict_types=1);

namespace App\Domain\Actions\Order;

use App\Domain\DTOs\Order\CreateOrderDTO;
use App\Domain\DTOs\Order\OrderResponseDTO;
use App\Domain\Services\Order\OrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
    }
}
