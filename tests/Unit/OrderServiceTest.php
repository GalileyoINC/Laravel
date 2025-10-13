<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\PayOrderDTO;
use App\Models\Finance\CreditCard;
use App\Models\Finance\Service;
use App\Models\Order;
use App\Models\User\User;
use App\Services\Order\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OrderService $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = new OrderService();
    }

    /** @test */
    public function create_order_creates_order_successfully(): void
    {
        // Arrange
        $user = User::factory()->create();
        $service = Service::factory()->create(['price' => 50.00]);
        
        $dto = new CreateOrderDTO(
            productId: $service->id,
            quantity: 2,
            paymentMethod: 'credit_card',
            totalAmount: 100.00,
            notes: 'Test order',
            productDetails: ['name' => 'Test Product']
        );

        // Act
        $result = $this->orderService->createOrder($dto, $user);

        // Assert
        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals($user->id, $result->id_user);
        $this->assertEquals($service->id, $result->id_product);
        $this->assertEquals(2, $result->quantity);
        $this->assertEquals(100.00, $result->total_amount);
        $this->assertEquals('credit_card', $result->payment_method);
        $this->assertEquals('pending', $result->status);
        $this->assertFalse($result->is_paid);
        $this->assertEquals('Test order', $result->notes);
        
        $this->assertDatabaseHas('orders', [
            'id_user' => $user->id,
            'id_product' => $service->id,
            'quantity' => 2,
            'total_amount' => 100.00,
            'status' => 'pending',
            'is_paid' => false,
        ]);
    }

    /** @test */
    public function create_order_calculates_total_amount_when_not_provided(): void
    {
        // Arrange
        $user = User::factory()->create();
        $service = Service::factory()->create(['price' => 25.50]);
        
        $dto = new CreateOrderDTO(
            productId: $service->id,
            quantity: 3,
            paymentMethod: 'credit_card',
            totalAmount: null, // Not provided
            notes: null,
            productDetails: []
        );

        // Act
        $result = $this->orderService->createOrder($dto, $user);

        // Assert
        $this->assertEquals(76.50, $result->total_amount); // 25.50 * 3
    }

    /** @test */
    public function create_order_throws_exception_when_product_not_found(): void
    {
        // Arrange
        $user = User::factory()->create();
        
        $dto = new CreateOrderDTO(
            productId: 999, // Non-existent product
            quantity: 1,
            paymentMethod: 'credit_card',
            totalAmount: 50.00,
            notes: null,
            productDetails: []
        );

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product not found');
        
        $this->orderService->createOrder($dto, $user);
    }

    /** @test */
    public function pay_order_updates_order_status_to_paid(): void
    {
        // Arrange
        $user = User::factory()->create();
        $service = Service::factory()->create();
        $creditCard = CreditCard::factory()->create(['id_user' => $user->id]);
        $order = Order::factory()->pending()->create([
            'id_user' => $user->id,
            'id_product' => $service->id,
            'is_paid' => false,
        ]);
        
        $dto = new PayOrderDTO(
            idOrder: $order->id,
            idCreditCard: $creditCard->id,
            paymentReference: 'PAY_123456',
            paymentDetails: ['gateway' => 'test_gateway']
        );

        // Act
        $result = $this->orderService->payOrder($dto, $user);

        // Assert
        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals('paid', $result->status);
        $this->assertTrue($result->is_paid);
        $this->assertEquals($creditCard->id, $result->id_credit_card);
        $this->assertEquals('PAY_123456', $result->payment_reference);
        
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
            'is_paid' => true,
            'id_credit_card' => $creditCard->id,
        ]);
    }

    /** @test */
    public function pay_order_throws_exception_when_order_not_found(): void
    {
        // Arrange
        $user = User::factory()->create();
        $creditCard = CreditCard::factory()->create(['id_user' => $user->id]);
        
        $dto = new PayOrderDTO(
            idOrder: 999, // Non-existent order
            idCreditCard: $creditCard->id,
            paymentReference: 'PAY_123456',
            paymentDetails: []
        );

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Order not found or unauthorized');
        
        $this->orderService->payOrder($dto, $user);
    }

    /** @test */
    public function pay_order_throws_exception_when_order_already_paid(): void
    {
        // Arrange
        $user = User::factory()->create();
        $service = Service::factory()->create();
        $creditCard = CreditCard::factory()->create(['id_user' => $user->id]);
        $order = Order::factory()->paid()->create([
            'id_user' => $user->id,
            'id_product' => $service->id,
        ]);
        
        $dto = new PayOrderDTO(
            idOrder: $order->id,
            idCreditCard: $creditCard->id,
            paymentReference: 'PAY_123456',
            paymentDetails: []
        );

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Order is already paid');
        
        $this->orderService->payOrder($dto, $user);
    }

    /** @test */
    public function pay_order_throws_exception_when_credit_card_not_found(): void
    {
        // Arrange
        $user = User::factory()->create();
        $service = Service::factory()->create();
        $order = Order::factory()->pending()->create([
            'id_user' => $user->id,
            'id_product' => $service->id,
        ]);
        
        $dto = new PayOrderDTO(
            idOrder: $order->id,
            idCreditCard: 999, // Non-existent credit card
            paymentReference: 'PAY_123456',
            paymentDetails: []
        );

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Credit card not found or unauthorized');
        
        $this->orderService->payOrder($dto, $user);
    }

    /** @test */
    public function get_test_order_returns_test_data(): void
    {
        // Act
        $result = $this->orderService->getTestOrder();

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('id', $result['data']);
        $this->assertArrayHasKey('products', $result['data']);
        $this->assertIsArray($result['data']['products']);
        $this->assertCount(2, $result['data']['products']);
    }
}
