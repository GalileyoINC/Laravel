<?php

declare(strict_types=1);

namespace App\Domain\Services\Order;

use App\Domain\DTOs\Order\CreateOrderDTO;
use App\Domain\DTOs\Order\PayOrderDTO;
use App\Models\User\User;

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
     *
     * @return array<string, mixed>
     */
    public function getTestOrder(): array;
}
