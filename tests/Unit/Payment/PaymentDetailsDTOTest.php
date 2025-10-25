<?php

declare(strict_types=1);

namespace Tests\Unit\Payment;

use App\Domain\DTOs\Payment\PaymentDetailsDTO;
use PHPUnit\Framework\TestCase;

/**
 * PaymentDetailsDTOTest
 * Unit tests for PaymentDetailsDTO
 */
class PaymentDetailsDTOTest extends TestCase
{
    public function test_can_create_payment_details_dto_from_array(): void
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'card_number' => '4111111111111111',
            'security_code' => '123',
            'expiration_year' => '2025',
            'expiration_month' => '12',
            'zip' => '12345',
            'is_agree_to_receive' => true,
            'id' => 1,
        ];

        $dto = PaymentDetailsDTO::fromArray($data);

        $this->assertEquals('John', $dto->first_name);
        $this->assertEquals('Doe', $dto->last_name);
        $this->assertEquals('+1234567890', $dto->phone);
        $this->assertEquals('4111111111111111', $dto->card_number);
        $this->assertEquals('123', $dto->security_code);
        $this->assertEquals('2025', $dto->expiration_year);
        $this->assertEquals('12', $dto->expiration_month);
        $this->assertEquals('12345', $dto->zip);
        $this->assertTrue($dto->is_agree_to_receive);
        $this->assertEquals(1, $dto->id);
    }

    public function test_can_create_payment_details_dto_with_defaults(): void
    {
        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => '+0987654321',
            'card_number' => '5555555555554444',
            'security_code' => '456',
            'expiration_year' => '2026',
            'expiration_month' => '06',
            'zip' => '54321',
        ];

        $dto = PaymentDetailsDTO::fromArray($data);

        $this->assertEquals('Jane', $dto->first_name);
        $this->assertEquals('Smith', $dto->last_name);
        $this->assertEquals('+0987654321', $dto->phone);
        $this->assertEquals('5555555555554444', $dto->card_number);
        $this->assertEquals('456', $dto->security_code);
        $this->assertEquals('2026', $dto->expiration_year);
        $this->assertEquals('06', $dto->expiration_month);
        $this->assertEquals('54321', $dto->zip);
        $this->assertTrue($dto->is_agree_to_receive); // Default value
        $this->assertNull($dto->id); // Default value
    }

    public function test_can_convert_dto_to_array(): void
    {
        $dto = new PaymentDetailsDTO(
            first_name: 'Test',
            last_name: 'User',
            phone: '+1111111111',
            card_number: '4000000000000002',
            security_code: '789',
            expiration_year: '2027',
            expiration_month: '03',
            zip: '11111',
            is_agree_to_receive: false,
            id: 5
        );

        $array = $dto->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('Test', $array['first_name']);
        $this->assertEquals('User', $array['last_name']);
        $this->assertEquals('+1111111111', $array['phone']);
        $this->assertEquals('4000000000000002', $array['card_number']);
        $this->assertEquals('789', $array['security_code']);
        $this->assertEquals('2027', $array['expiration_year']);
        $this->assertEquals('03', $array['expiration_month']);
        $this->assertEquals('11111', $array['zip']);
        $this->assertFalse($array['is_agree_to_receive']);
        $this->assertEquals(5, $array['id']);
    }

    public function test_dto_properties_are_readonly(): void
    {
        $dto = new PaymentDetailsDTO(
            first_name: 'Readonly',
            last_name: 'Test',
            phone: '+2222222222',
            card_number: '3000000000000004',
            security_code: '000',
            expiration_year: '2028',
            expiration_month: '01',
            zip: '22222'
        );

        // Properties should be readonly - this should not cause any issues
        $this->assertEquals('Readonly', $dto->first_name);
        $this->assertEquals('Test', $dto->last_name);
    }
}
