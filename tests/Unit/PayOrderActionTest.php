<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Domain\Actions\Order\PayOrderAction;
use App\Domain\DTOs\Order\PayOrderDTO;
use App\Domain\Services\Order\OrderServiceInterface;
use App\Models\User\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class PayOrderActionTest extends TestCase
{
    use RefreshDatabase;

    protected PayOrderAction $payOrderAction;

    protected $mockOrderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockOrderService = Mockery::mock(OrderServiceInterface::class);
        $this->payOrderAction = new PayOrderAction($this->mockOrderService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function execute_pays_order_successfully(): void
    {
        // Arrange
        $user = User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);

        $creditCard = \App\Models\Finance\CreditCard::factory()->create(['id_user' => $user->id]);
        $order = \App\Models\Order::factory()->paid()->create([
            'id_user' => $user->id,
            'id_credit_card' => $creditCard->id,
        ]);

        $this->mockOrderService
            ->shouldReceive('payOrder')
            ->once()
            ->with(Mockery::type(PayOrderDTO::class), $user)
            ->andReturn($order);

        $paymentData = [
            'id_order' => $order->id,
            'id_credit_card' => $creditCard->id,
            'payment_reference' => 'PAY_123456',
            'payment_details' => ['gateway' => 'test_gateway'],
        ];

        // Act
        $result = $this->payOrderAction->execute($paymentData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());

        $responseData = $result->getData(true);
        $this->assertEquals($order->id, $responseData['id']);
        $this->assertEquals('paid', $responseData['status']);
        $this->assertTrue($responseData['is_paid']);
    }

    /** @test */
    public function execute_returns_validation_error_for_invalid_data(): void
    {
        // Arrange
        $user = User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);

        $invalidData = [
            'id_order' => 0, // Invalid order ID
            'id_credit_card' => 0, // Invalid credit card ID
        ];

        // Act
        $result = $this->payOrderAction->execute($invalidData);

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

        $paymentData = [
            'id_order' => 1,
            'id_credit_card' => 1,
        ];

        // Act
        $result = $this->payOrderAction->execute($paymentData);

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

        $creditCard = \App\Models\Finance\CreditCard::factory()->create(['id_user' => $user->id]);

        $this->mockOrderService
            ->shouldReceive('payOrder')
            ->once()
            ->andThrow(new Exception('Payment failed'));

        $paymentData = [
            'id_order' => 1,
            'id_credit_card' => $creditCard->id,
        ];

        // Act
        $result = $this->payOrderAction->execute($paymentData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(500, $result->getStatusCode());

        $responseData = $result->getData(true);
        $this->assertEquals('An internal server error occurred.', $responseData['error']);
        $this->assertEquals(500, $responseData['code']);
    }
}
