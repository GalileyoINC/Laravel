<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Domain\DTOs\Order\CreateOrderDTO;
use Illuminate\Http\Request;
use Tests\TestCase;

class CreateOrderDTOTest extends TestCase
{
    /** @test */
    public function constructor_creates_dto_with_provided_values(): void
    {
        // Arrange
        $productId = 1;
        $quantity = 2;
        $paymentMethod = 'credit_card';
        $totalAmount = 100.00;
        $notes = 'Test order';
        $productDetails = ['name' => 'Test Product'];

        // Act
        $dto = new CreateOrderDTO(
            productId: $productId,
            quantity: $quantity,
            paymentMethod: $paymentMethod,
            totalAmount: $totalAmount,
            notes: $notes,
            productDetails: $productDetails
        );

        // Assert
        $this->assertEquals($productId, $dto->productId);
        $this->assertEquals($quantity, $dto->quantity);
        $this->assertEquals($paymentMethod, $dto->paymentMethod);
        $this->assertEquals($totalAmount, $dto->totalAmount);
        $this->assertEquals($notes, $dto->notes);
        $this->assertEquals($productDetails, $dto->productDetails);
    }

    /** @test */
    public function constructor_uses_default_values_for_optional_parameters(): void
    {
        // Act
        $dto = new CreateOrderDTO(
            productId: 1,
            quantity: 1,
            paymentMethod: 'credit_card'
        );

        // Assert
        $this->assertEquals(1, $dto->productId);
        $this->assertEquals(1, $dto->quantity);
        $this->assertEquals('credit_card', $dto->paymentMethod);
        $this->assertNull($dto->totalAmount);
        $this->assertNull($dto->notes);
        $this->assertEquals([], $dto->productDetails);
    }

    /** @test */
    public function from_array_creates_dto_with_provided_values(): void
    {
        // Arrange
        $data = [
            'product_id' => 2,
            'quantity' => 3,
            'payment_method' => 'paypal',
            'total_amount' => 150.00,
            'notes' => 'Test order from array',
            'product_details' => ['name' => 'Test Product', 'description' => 'Test Description'],
        ];

        // Act
        $dto = CreateOrderDTO::fromArray($data);

        // Assert
        $this->assertEquals(2, $dto->productId);
        $this->assertEquals(3, $dto->quantity);
        $this->assertEquals('paypal', $dto->paymentMethod);
        $this->assertEquals(150.00, $dto->totalAmount);
        $this->assertEquals('Test order from array', $dto->notes);
        $this->assertEquals(['name' => 'Test Product', 'description' => 'Test Description'], $dto->productDetails);
    }

    /** @test */
    public function from_array_uses_default_values_for_missing_keys(): void
    {
        // Arrange
        $data = [
            'product_id' => 1,
            'quantity' => 1,
        ];

        // Act
        $dto = CreateOrderDTO::fromArray($data);

        // Assert
        $this->assertEquals(1, $dto->productId);
        $this->assertEquals(1, $dto->quantity);
        $this->assertEquals('credit_card', $dto->paymentMethod);
        $this->assertNull($dto->totalAmount);
        $this->assertNull($dto->notes);
        $this->assertEquals([], $dto->productDetails);
    }

    /** @test */
    public function from_request_creates_dto_from_request_data(): void
    {
        // Arrange
        $request = Request::create('/test', 'POST', [
            'product_id' => 3,
            'quantity' => 2,
            'payment_method' => 'apple_pay',
            'total_amount' => 200.00,
            'notes' => 'Test order from request',
            'product_details' => ['name' => 'Request Product'],
        ]);

        // Act
        $dto = CreateOrderDTO::fromRequest($request);

        // Assert
        $this->assertEquals(3, $dto->productId);
        $this->assertEquals(2, $dto->quantity);
        $this->assertEquals('apple_pay', $dto->paymentMethod);
        $this->assertEquals(200.00, $dto->totalAmount);
        $this->assertEquals('Test order from request', $dto->notes);
        $this->assertEquals(['name' => 'Request Product'], $dto->productDetails);
    }

    /** @test */
    public function to_array_returns_correct_array_structure(): void
    {
        // Arrange
        $dto = new CreateOrderDTO(
            productId: 1,
            quantity: 2,
            paymentMethod: 'credit_card',
            totalAmount: 100.00,
            notes: 'Test order',
            productDetails: ['name' => 'Test Product']
        );

        // Act
        $result = $dto->toArray();

        // Assert
        $expected = [
            'product_id' => 1,
            'quantity' => 2,
            'payment_method' => 'credit_card',
            'total_amount' => 100.00,
            'notes' => 'Test order',
            'product_details' => ['name' => 'Test Product'],
        ];
        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function validate_returns_true_for_valid_data(): void
    {
        // Arrange
        $dto = new CreateOrderDTO(
            productId: 1,
            quantity: 2,
            paymentMethod: 'credit_card'
        );

        // Act
        $result = $dto->validate();

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function validate_returns_false_for_invalid_product_id(): void
    {
        // Arrange
        $dto = new CreateOrderDTO(
            productId: 0, // Invalid
            quantity: 2,
            paymentMethod: 'credit_card'
        );

        // Act
        $result = $dto->validate();

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function validate_returns_false_for_invalid_quantity(): void
    {
        // Arrange
        $dto = new CreateOrderDTO(
            productId: 1,
            quantity: 0, // Invalid
            paymentMethod: 'credit_card'
        );

        // Act
        $result = $dto->validate();

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function validate_returns_false_for_empty_payment_method(): void
    {
        // Arrange
        $dto = new CreateOrderDTO(
            productId: 1,
            quantity: 2,
            paymentMethod: '' // Invalid
        );

        // Act
        $result = $dto->validate();

        // Assert
        $this->assertFalse($result);
    }
}
