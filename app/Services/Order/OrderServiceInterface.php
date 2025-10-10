<?php

namespace App\Services\Order;

use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\PayOrderDTO;
use App\Models\User;

/**
 * Order service interface
 */
interface OrderServiceInterface
{
    /**
     * Create new order
     *
     * @param CreateOrderDTO $dto
     * @param User $user
     * @return mixed
     */
    public function createOrder(CreateOrderDTO $dto, User $user);

    /**
     * Pay for order
     *
     * @param PayOrderDTO $dto
     * @param User $user
     * @return mixed
     */
    public function payOrder(PayOrderDTO $dto, User $user);

    /**
     * Get test order data
     *
     * @return array
     */
    public function getTestOrder(): array;
}
