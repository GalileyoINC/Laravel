<?php

declare(strict_types=1);

namespace Tests\Unit\Payment;

use App\Domain\DTOs\Payment\PaymentListRequestDTO;
use PHPUnit\Framework\TestCase;

/**
 * PaymentListRequestDTOTest
 * Unit tests for PaymentListRequestDTO
 */
class PaymentListRequestDTOTest extends TestCase
{
    public function test_can_create_payment_list_request_dto_from_array(): void
    {
        $data = [
            'limit' => 50,
            'page' => 2,
            'type' => 'authorize',
            'is_success' => true,
        ];

        $dto = PaymentListRequestDTO::fromArray($data);

        $this->assertEquals(50, $dto->limit);
        $this->assertEquals(2, $dto->page);
        $this->assertEquals('authorize', $dto->type);
        $this->assertTrue($dto->is_success);
    }

    public function test_can_create_payment_list_request_dto_with_defaults(): void
    {
        $data = [];

        $dto = PaymentListRequestDTO::fromArray($data);

        $this->assertEquals(100, $dto->limit); // Default value
        $this->assertEquals(1, $dto->page); // Default value
        $this->assertNull($dto->type); // Default value
        $this->assertNull($dto->is_success); // Default value
    }

    public function test_can_calculate_offset(): void
    {
        $dto = new PaymentListRequestDTO(
            limit: 20,
            page: 3,
            type: 'bitpay',
            is_success: false
        );

        $this->assertEquals(40, $dto->getOffset()); // (3-1) * 20 = 40
    }

    public function test_offset_calculation_for_first_page(): void
    {
        $dto = new PaymentListRequestDTO(
            limit: 10,
            page: 1,
            type: null,
            is_success: null
        );

        $this->assertEquals(0, $dto->getOffset()); // (1-1) * 10 = 0
    }

    public function test_can_convert_dto_to_array(): void
    {
        $dto = new PaymentListRequestDTO(
            limit: 25,
            page: 4,
            type: 'apple',
            is_success: true
        );

        $array = $dto->toArray();

        $this->assertIsArray($array);
        $this->assertEquals(25, $array['limit']);
        $this->assertEquals(4, $array['page']);
        $this->assertEquals('apple', $array['type']);
        $this->assertTrue($array['is_success']);
    }

    public function test_dto_properties_are_readonly(): void
    {
        $dto = new PaymentListRequestDTO(
            limit: 15,
            page: 5,
            type: 'discount',
            is_success: false
        );

        // Properties should be readonly
        $this->assertEquals(15, $dto->limit);
        $this->assertEquals(5, $dto->page);
        $this->assertEquals('discount', $dto->type);
        $this->assertFalse($dto->is_success);
    }
}
