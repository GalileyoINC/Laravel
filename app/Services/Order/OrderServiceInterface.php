<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\PayOrderDTO;
use App\Models\User\User\User;

/**
 * Order service interface
 */
interface OrderServiceInterface
{
    /**
     * Create new order
     *
     * @return mixed
     */
    public function createOrder(CreateOrderDTO $dto, User $user);

    /**
     * Pay for order
     *
     * @return mixed
     */
    public function payOrder(PayOrderDTO $dto, User $user);

    /**
     * Get test order data
     */
    public function getTestOrder(): array;
}
