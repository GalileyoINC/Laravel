<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\DTOs\Order\PayOrderDTO;
use Illuminate\Http\Request;
use Tests\TestCase;

class PayOrderDTOTest extends TestCase
{
    /** @test */
    public function constructor_creates_dto_with_provided_values(): void
    {
        // Arrange
        $idOrder = 1;
        $idCreditCard = 2;
        $paymentReference = 'PAY_123456';
        $paymentDetails = ['gateway' => 'test_gateway'];

        // Act
        $dto = new PayOrderDTO(
            idOrder: $idOrder,
            idCreditCard: $idCreditCard,
            paymentReference: $paymentReference,
            paymentDetails: $paymentDetails
        );

        // Assert
        $this->assertEquals($idOrder, $dto->idOrder);
        $this->assertEquals($idCreditCard, $dto->idCreditCard);
        $this->assertEquals($paymentReference, $dto->paymentReference);
        $this->assertEquals($paymentDetails, $dto->paymentDetails);
    }

    /** @test */
    public function constructor_uses_default_values_for_optional_parameters(): void
    {
        // Act
        $dto = new PayOrderDTO(
            idOrder: 1,
            idCreditCard: 2
        );

        // Assert
        $this->assertEquals(1, $dto->idOrder);
        $this->assertEquals(2, $dto->idCreditCard);
        $this->assertNull($dto->paymentReference);
        $this->assertEquals([], $dto->paymentDetails);
    }

    /** @test */
    public function from_array_creates_dto_with_provided_values(): void
    {
        // Arrange
        $data = [
            'id_order' => 3,
            'id_credit_card' => 4,
            'payment_reference' => 'PAY_789012',
            'payment_details' => ['gateway' => 'stripe', 'transaction_id' => 'txn_123'],
        ];

        // Act
        $dto = PayOrderDTO::fromArray($data);

        // Assert
        $this->assertEquals(3, $dto->idOrder);
        $this->assertEquals(4, $dto->idCreditCard);
        $this->assertEquals('PAY_789012', $dto->paymentReference);
        $this->assertEquals(['gateway' => 'stripe', 'transaction_id' => 'txn_123'], $dto->paymentDetails);
    }

    /** @test */
    public function from_array_uses_default_values_for_missing_keys(): void
    {
        // Arrange
        $data = [
            'id_order' => 1,
            'id_credit_card' => 2,
        ];

        // Act
        $dto = PayOrderDTO::fromArray($data);

        // Assert
        $this->assertEquals(1, $dto->idOrder);
        $this->assertEquals(2, $dto->idCreditCard);
        $this->assertNull($dto->paymentReference);
        $this->assertEquals([], $dto->paymentDetails);
    }

    /** @test */
    public function from_request_creates_dto_from_request_data(): void
    {
        // Arrange
        $request = Request::create('/test', 'POST', [
            'id_order' => 5,
            'id_credit_card' => 6,
            'payment_reference' => 'PAY_345678',
            'payment_details' => ['gateway' => 'paypal'],
        ]);

        // Act
        $dto = PayOrderDTO::fromRequest($request);

        // Assert
        $this->assertEquals(5, $dto->idOrder);
        $this->assertEquals(6, $dto->idCreditCard);
        $this->assertEquals('PAY_345678', $dto->paymentReference);
        $this->assertEquals(['gateway' => 'paypal'], $dto->paymentDetails);
    }

    /** @test */
    public function to_array_returns_correct_array_structure(): void
    {
        // Arrange
        $dto = new PayOrderDTO(
            idOrder: 1,
            idCreditCard: 2,
            paymentReference: 'PAY_123456',
            paymentDetails: ['gateway' => 'test_gateway']
        );

        // Act
        $result = $dto->toArray();

        // Assert
        $expected = [
            'id_order' => 1,
            'id_credit_card' => 2,
            'payment_reference' => 'PAY_123456',
            'payment_details' => ['gateway' => 'test_gateway'],
        ];
        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function validate_returns_true_for_valid_data(): void
    {
        // Arrange
        $dto = new PayOrderDTO(
            idOrder: 1,
            idCreditCard: 2
        );

        // Act
        $result = $dto->validate();

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function validate_returns_false_for_invalid_order_id(): void
    {
        // Arrange
        $dto = new PayOrderDTO(
            idOrder: 0, // Invalid
            idCreditCard: 2
        );

        // Act
        $result = $dto->validate();

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function validate_returns_false_for_invalid_credit_card_id(): void
    {
        // Arrange
        $dto = new PayOrderDTO(
            idOrder: 1,
            idCreditCard: 0 // Invalid
        );

        // Act
        $result = $dto->validate();

        // Assert
        $this->assertFalse($result);
    }
}
