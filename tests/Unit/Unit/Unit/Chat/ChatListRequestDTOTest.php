<?php

declare(strict_types=1);

namespace Tests\Unit\Unit\Unit\Chat;

use App\Domain\DTOs\Chat\ChatListRequestDTO;
use Illuminate\Http\Request;
use Tests\TestCase;

class ChatListRequestDTOTest extends TestCase
{
    /** @test */
    public function from_array_creates_dto_with_provided_values(): void
    {
        // Arrange
        $data = [
            'limit' => 50,
            'offset' => 10,
            'filter' => ['type' => 'group'],
        ];

        // Act
        $dto = ChatListRequestDTO::fromArray($data);

        // Assert
        $this->assertEquals(50, $dto->limit);
        $this->assertEquals(10, $dto->offset);
        $this->assertEquals(['type' => 'group'], $dto->filter);
    }

    /** @test */
    public function from_array_uses_default_values_when_missing(): void
    {
        // Arrange
        $data = [];

        // Act
        $dto = ChatListRequestDTO::fromArray($data);

        // Assert
        $this->assertEquals(20, $dto->limit);
        $this->assertEquals(0, $dto->offset);
        $this->assertEquals([], $dto->filter);
    }

    /** @test */
    public function from_request_creates_dto_from_request_data(): void
    {
        // Arrange
        $request = new Request([
            'limit' => 30,
            'offset' => 5,
            'filter' => ['status' => 'active'],
        ]);

        // Act
        $dto = ChatListRequestDTO::fromRequest($request);

        // Assert
        $this->assertEquals(30, $dto->limit);
        $this->assertEquals(5, $dto->offset);
        $this->assertEquals(['status' => 'active'], $dto->filter);
    }

    /** @test */
    public function from_request_uses_default_values_when_missing(): void
    {
        // Arrange
        $request = new Request();

        // Act
        $dto = ChatListRequestDTO::fromRequest($request);

        // Assert
        $this->assertEquals(20, $dto->limit);
        $this->assertEquals(0, $dto->offset);
        $this->assertEquals([], $dto->filter);
    }

    /** @test */
    public function to_array_returns_correct_structure(): void
    {
        // Arrange
        $dto = new ChatListRequestDTO(
            limit: 25,
            offset: 15,
            filter: ['category' => 'work']
        );

        // Act
        $array = $dto->toArray();

        // Assert
        $this->assertIsArray($array);
        $this->assertEquals(25, $array['limit']);
        $this->assertEquals(15, $array['offset']);
        $this->assertEquals(['category' => 'work'], $array['filter']);
    }

    /** @test */
    public function validate_returns_true_for_valid_data(): void
    {
        // Arrange
        $dto = new ChatListRequestDTO(
            limit: 20,
            offset: 0,
            filter: []
        );

        // Act
        $isValid = $dto->validate();

        // Assert
        $this->assertTrue($isValid);
    }

    /** @test */
    public function validate_returns_false_for_invalid_limit(): void
    {
        // Arrange
        $dto = new ChatListRequestDTO(
            limit: 0,
            offset: 0,
            filter: []
        );

        // Act
        $isValid = $dto->validate();

        // Assert
        $this->assertFalse($isValid);
    }

    /** @test */
    public function validate_returns_false_for_negative_offset(): void
    {
        // Arrange
        $dto = new ChatListRequestDTO(
            limit: 20,
            offset: -1,
            filter: []
        );

        // Act
        $isValid = $dto->validate();

        // Assert
        $this->assertFalse($isValid);
    }

    /** @test */
    public function validate_returns_true_for_positive_offset(): void
    {
        // Arrange
        $dto = new ChatListRequestDTO(
            limit: 20,
            offset: 10,
            filter: []
        );

        // Act
        $isValid = $dto->validate();

        // Assert
        $this->assertTrue($isValid);
    }
}
