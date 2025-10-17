<?php

declare(strict_types=1);

namespace Tests\Unit\Unit\Unit\Device;

use App\Domain\DTOs\Device\DeviceListRequestDTO;
use Tests\TestCase;

class DeviceListRequestDTOTest extends TestCase
{
    /** @test */
    public function constructor_creates_dto_with_provided_values(): void
    {
        // Arrange & Act
        $dto = new DeviceListRequestDTO(
            page: 2,
            limit: 20,
            search: 'test search',
            user_id: 123,
            os: 'ios'
        );

        // Assert
        $this->assertEquals(2, $dto->page);
        $this->assertEquals(20, $dto->limit);
        $this->assertEquals('test search', $dto->search);
        $this->assertEquals(123, $dto->user_id);
        $this->assertEquals('ios', $dto->os);
    }

    /** @test */
    public function constructor_accepts_null_values(): void
    {
        // Arrange & Act
        $dto = new DeviceListRequestDTO(
            page: 1,
            limit: 10,
            search: null,
            user_id: null,
            os: null
        );

        // Assert
        $this->assertEquals(1, $dto->page);
        $this->assertEquals(10, $dto->limit);
        $this->assertNull($dto->search);
        $this->assertNull($dto->user_id);
        $this->assertNull($dto->os);
    }

    /** @test */
    public function constructor_accepts_empty_string_values(): void
    {
        // Arrange & Act
        $dto = new DeviceListRequestDTO(
            page: 1,
            limit: 10,
            search: '',
            user_id: null,
            os: ''
        );

        // Assert
        $this->assertEquals(1, $dto->page);
        $this->assertEquals(10, $dto->limit);
        $this->assertEquals('', $dto->search);
        $this->assertNull($dto->user_id);
        $this->assertEquals('', $dto->os);
    }

    /** @test */
    public function constructor_accepts_zero_values(): void
    {
        // Arrange & Act
        $dto = new DeviceListRequestDTO(
            page: 0,
            limit: 0,
            search: null,
            user_id: 0,
            os: null
        );

        // Assert
        $this->assertEquals(0, $dto->page);
        $this->assertEquals(0, $dto->limit);
        $this->assertNull($dto->search);
        $this->assertEquals(0, $dto->user_id);
        $this->assertNull($dto->os);
    }

    /** @test */
    public function constructor_accepts_negative_values(): void
    {
        // Arrange & Act
        $dto = new DeviceListRequestDTO(
            page: -1,
            limit: -5,
            search: null,
            user_id: -10,
            os: null
        );

        // Assert
        $this->assertEquals(-1, $dto->page);
        $this->assertEquals(-5, $dto->limit);
        $this->assertNull($dto->search);
        $this->assertEquals(-10, $dto->user_id);
        $this->assertNull($dto->os);
    }

    /** @test */
    public function constructor_accepts_large_values(): void
    {
        // Arrange & Act
        $dto = new DeviceListRequestDTO(
            page: 999999,
            limit: 1000,
            search: 'very long search string with special characters !@#$%^&*()',
            user_id: 999999,
            os: 'very-long-operating-system-name'
        );

        // Assert
        $this->assertEquals(999999, $dto->page);
        $this->assertEquals(1000, $dto->limit);
        $this->assertEquals('very long search string with special characters !@#$%^&*()', $dto->search);
        $this->assertEquals(999999, $dto->user_id);
        $this->assertEquals('very-long-operating-system-name', $dto->os);
    }
}
