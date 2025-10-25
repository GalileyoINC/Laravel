<?php

declare(strict_types=1);

namespace Tests\Unit\Payment;

use App\Domain\DTOs\Payment\PaymentDetailsDTO;
use App\Domain\Services\Payment\PaymentService;
use App\Models\CreditCard;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * SimplePaymentServiceTest
 * Simplified unit tests for PaymentService without complex database constraints
 */
class SimplePaymentServiceTest extends TestCase
{
    private PaymentService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentService = new PaymentService();
    }

    public function test_can_create_payment_details_dto(): void
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'card_number' => '************1111',
            'security_code' => '123',
            'expiration_year' => '2025',
            'expiration_month' => '12',
            'zip' => '12345',
            'is_agree_to_receive' => true,
        ];

        $dto = PaymentDetailsDTO::fromArray($data);

        $this->assertEquals('John', $dto->first_name);
        $this->assertEquals('Doe', $dto->last_name);
        $this->assertEquals('************1111', $dto->card_number);
        $this->assertEquals('123', $dto->security_code);
        $this->assertEquals('2025', $dto->expiration_year);
        $this->assertEquals('12', $dto->expiration_month);
        $this->assertTrue($dto->is_agree_to_receive);
    }

    public function test_can_convert_dto_to_array(): void
    {
        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => '+0987654321',
            'card_number' => '************2222',
            'security_code' => '456',
            'expiration_year' => '2026',
            'expiration_month' => '6',
            'zip' => '54321',
            'is_agree_to_receive' => false,
        ];

        $dto = PaymentDetailsDTO::fromArray($data);
        $array = $dto->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('Jane', $array['first_name']);
        $this->assertEquals('Smith', $array['last_name']);
        $this->assertEquals('************2222', $array['card_number']);
        $this->assertEquals('456', $array['security_code']);
        $this->assertEquals('2026', $array['expiration_year']);
        $this->assertEquals('6', $array['expiration_month']);
        $this->assertFalse($array['is_agree_to_receive']);
    }

    public function test_dto_properties_are_readonly(): void
    {
        $data = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '+1111111111',
            'card_number' => '************3333',
            'security_code' => '789',
            'expiration_year' => '2027',
            'expiration_month' => '3',
            'zip' => '11111',
            'is_agree_to_receive' => true,
        ];

        $dto = PaymentDetailsDTO::fromArray($data);

        // Test that properties are readonly by trying to modify them
        $this->expectException(\Error::class);
        $dto->first_name = 'Modified';
    }

    public function test_payment_service_can_be_instantiated(): void
    {
        $this->assertInstanceOf(PaymentService::class, $this->paymentService);
    }

    public function test_credit_card_model_exists(): void
    {
        $this->assertTrue(class_exists(CreditCard::class));
    }

    public function test_user_model_exists(): void
    {
        $this->assertTrue(class_exists(User::class));
    }
}
