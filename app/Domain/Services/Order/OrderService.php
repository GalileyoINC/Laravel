<?php

declare(strict_types=1);

namespace App\Domain\Services\Order;

use App\Domain\DTOs\Order\CreateOrderDTO;
use App\Domain\DTOs\Order\PayOrderDTO;
use App\Models\Finance\CreditCard;
use App\Models\Finance\Service;
use App\Models\Order;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Order service implementation
 */
class OrderService implements OrderServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function createOrder(CreateOrderDTO $dto, User $user)
    {
        try {
            DB::beginTransaction();

            // Get product details
            $product = Service::find($dto->productId);
            if (! $product) {
                throw new Exception('Product not found');
            }

            // Calculate total amount if not provided
            $totalAmount = $dto->totalAmount ?? ($product->price * $dto->quantity);

            // Create order
            $order = Order::create([
                'id_user' => $user->id,
                'id_product' => $dto->productId,
                'quantity' => $dto->quantity,
                'total_amount' => $totalAmount,
                'payment_method' => $dto->paymentMethod,
                'status' => 'pending',
                'is_paid' => false,
                'notes' => $dto->notes,
                'product_details' => json_encode($dto->productDetails),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return $order;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('OrderService createOrder error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function payOrder(PayOrderDTO $dto, User $user)
    {
        try {
            DB::beginTransaction();

            // Get order
            $order = Order::where('id', $dto->idOrder)
                ->where('id_user', $user->id)
                ->first();

            if (! $order) {
                throw new Exception('Order not found or unauthorized');
            }

            if ($order->is_paid) {
                throw new Exception('Order is already paid');
            }

            // Get credit card
            $creditCard = CreditCard::where('id', $dto->idCreditCard)
                ->where('id_user', $user->id)
                ->first();

            if (! $creditCard) {
                throw new Exception('Credit card not found or unauthorized');
            }

            // Process payment (simplified - in real app would integrate with payment gateway)
            $order->update([
                'id_credit_card' => $dto->idCreditCard,
                'status' => 'paid',
                'is_paid' => true,
                'payment_reference' => $dto->paymentReference ?? 'PAY_'.time(),
                'payment_details' => json_encode($dto->paymentDetails),
                'updated_at' => now(),
            ]);

            DB::commit();

            return $order;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('OrderService payOrder error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    public function getTestOrder(): array
    {
        return [
            'status' => 'success',
            'data' => [
                'id' => 1,
                'credit' => '0.00',
                'total_to_pay' => '80.00',
                'products' => [
                    [
                        'type' => 'subscribe',
                        'description' => 'Family (Mar 11, 2024 - Mar 07, 2025)',
                        'price' => '70.00',
                        'for' => null,
                        'begin_at' => '2024-03-11 14:48:30',
                        'end_at' => '2025-03-07 15:40:20',
                        'name' => 'Family',
                    ],
                    [
                        'type' => 'alerts',
                        'description' => 'Alert Kits (100 Alerts)',
                        'price' => '15.00',
                        'for' => null,
                    ],
                ],
                'is_paid' => false,
            ],
        ];
    }
}
