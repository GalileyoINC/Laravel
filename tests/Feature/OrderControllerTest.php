<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Finance\CreditCard;
use App\Models\Finance\Service;
use App\Models\Order;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function create_order_endpoint_creates_order_successfully(): void
    {
        // Arrange
        $user = User::factory()->create();
        $service = \App\Models\Finance\Service::factory()->create(['price' => 50.00]);
        
        $this->actingAs($user, 'sanctum');

        $orderData = [
            'product_id' => $service->id,
            'quantity' => 2,
            'payment_method' => 'credit_card',
            'total_amount' => 100.00,
            'notes' => 'Test order',
            'product_details' => ['name' => 'Test Product'],
        ];

        // Act
        $response = $this->postJson('/api/v1/order/create', $orderData);

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'status',
                'total_amount',
                'is_paid',
                'payment_method',
                'created_at',
                'products',
                'payment_details',
            ]);

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
    public function create_order_endpoint_returns_validation_error_for_invalid_data(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $invalidData = [
            'product_id' => 0, // Invalid
            'quantity' => 0,    // Invalid
            'payment_method' => '', // Invalid
        ];

        // Act
        $response = $this->postJson('/api/v1/order/create', $invalidData);

        // Assert
        $response->assertStatus(400)
            ->assertJsonStructure([
                'errors',
                'message',
            ]);
    }

    /** @test */
    public function create_order_endpoint_returns_unauthorized_when_not_authenticated(): void
    {
        // Arrange
        $orderData = [
            'product_id' => 1,
            'quantity' => 1,
            'payment_method' => 'credit_card',
        ];

        // Act
        $response = $this->postJson('/api/v1/order/create', $orderData);

        // Assert
        $response->assertStatus(401);
    }

    /** @test */
    public function pay_order_endpoint_pays_order_successfully(): void
    {
        // Arrange
        $user = User::factory()->create();
        $service = \App\Models\Finance\Service::factory()->create();
        $creditCard = \App\Models\Finance\CreditCard::factory()->create(['id_user' => $user->id]);
        $order = \App\Models\Order::factory()->pending()->create([
            'id_user' => $user->id,
            'id_product' => $service->id,
        ]);
        
        $this->actingAs($user, 'sanctum');

        $paymentData = [
            'id_order' => $order->id,
            'id_credit_card' => $creditCard->id,
            'payment_reference' => 'PAY_123456',
            'payment_details' => ['gateway' => 'test_gateway'],
        ];

        // Act
        $response = $this->postJson('/api/v1/order/pay', $paymentData);

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'status',
                'total_amount',
                'is_paid',
                'payment_method',
                'created_at',
                'products',
                'payment_details',
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
            'is_paid' => true,
            'id_credit_card' => $creditCard->id,
        ]);
    }

    /** @test */
    public function pay_order_endpoint_returns_validation_error_for_invalid_data(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $invalidData = [
            'id_order' => 0, // Invalid
            'id_credit_card' => 0, // Invalid
        ];

        // Act
        $response = $this->postJson('/api/v1/order/pay', $invalidData);

        // Assert
        $response->assertStatus(400)
            ->assertJsonStructure([
                'errors',
                'message',
            ]);
    }

    /** @test */
    public function pay_order_endpoint_returns_unauthorized_when_not_authenticated(): void
    {
        // Arrange
        $paymentData = [
            'id_order' => 1,
            'id_credit_card' => 1,
        ];

        // Act
        $response = $this->postJson('/api/v1/order/pay', $paymentData);

        // Assert
        $response->assertStatus(401);
    }

    /** @test */
    public function test_order_endpoint_returns_test_data(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        // Act
        $response = $this->getJson('/api/v1/order/test');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'credit',
                    'total_to_pay',
                    'products',
                    'is_paid',
                ],
            ])
            ->assertJson([
                'status' => 'success',
            ]);
    }

    /** @test */
    public function test_order_endpoint_returns_unauthorized_when_not_authenticated(): void
    {
        // Act
        $response = $this->getJson('/api/v1/order/test');

        // Assert
        $response->assertStatus(401);
    }
}
