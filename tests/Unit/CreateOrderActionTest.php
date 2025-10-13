<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Actions\Order\CreateOrderAction;
use App\DTOs\Order\CreateOrderDTO;
use App\Models\Finance\Service;
use App\Models\Order;
use App\Models\User\User;
use App\Services\Order\OrderServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class CreateOrderActionTest extends TestCase
{
    use RefreshDatabase;

    protected CreateOrderAction $createOrderAction;
    protected $mockOrderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockOrderService = Mockery::mock(OrderServiceInterface::class);
        $this->createOrderAction = new CreateOrderAction($this->mockOrderService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function execute_creates_order_successfully(): void
    {
        // Arrange
        $user = User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);
        
        $service = \App\Models\Finance\Service::factory()->create();
        $order = \App\Models\Order::factory()->create([
            'id_user' => $user->id,
            'id_product' => $service->id,
            'status' => 'pending',
        ]);

        $this->mockOrderService
            ->shouldReceive('createOrder')
            ->once()
            ->with(Mockery::type(CreateOrderDTO::class), $user)
            ->andReturn($order);

        $orderData = [
            'product_id' => $service->id,
            'quantity' => 2,
            'payment_method' => 'credit_card',
            'total_amount' => 100.00,
            'notes' => 'Test order',
            'product_details' => ['name' => 'Test Product'],
        ];

        // Act
        $result = $this->createOrderAction->execute($orderData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
        
        $responseData = $result->getData(true);
        $this->assertEquals($order->id, $responseData['id']);
        $this->assertEquals('pending', $responseData['status']);
    }

    /** @test */
    public function execute_returns_validation_error_for_invalid_data(): void
    {
        // Arrange
        $user = User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);

        $invalidData = [
            'product_id' => 0, // Invalid product ID
            'quantity' => 0,   // Invalid quantity
            'payment_method' => '', // Empty payment method
        ];

        // Act
        $result = $this->createOrderAction->execute($invalidData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(400, $result->getStatusCode());
        
        $responseData = $result->getData(true);
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('message', $responseData);
    }

    /** @test */
    public function execute_returns_unauthorized_error_when_user_not_authenticated(): void
    {
        // Arrange
        Auth::shouldReceive('user')->andReturn(null);

        $orderData = [
            'product_id' => 1,
            'quantity' => 1,
            'payment_method' => 'credit_card',
        ];

        // Act
        $result = $this->createOrderAction->execute($orderData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(401, $result->getStatusCode());
        
        $responseData = $result->getData(true);
        $this->assertEquals('User not authenticated', $responseData['error']);
        $this->assertEquals(401, $responseData['code']);
    }

    /** @test */
    public function execute_returns_server_error_when_service_throws_exception(): void
    {
        // Arrange
        $user = User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);
        
        $service = \App\Models\Finance\Service::factory()->create();

        $this->mockOrderService
            ->shouldReceive('createOrder')
            ->once()
            ->andThrow(new \Exception('Service error'));

        $orderData = [
            'product_id' => $service->id,
            'quantity' => 1,
            'payment_method' => 'credit_card',
        ];

        // Act
        $result = $this->createOrderAction->execute($orderData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(500, $result->getStatusCode());
        
        $responseData = $result->getData(true);
        $this->assertEquals('An internal server error occurred.', $responseData['error']);
        $this->assertEquals(500, $responseData['code']);
    }
}
