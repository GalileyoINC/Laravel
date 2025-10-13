<?php

declare(strict_types=1);

namespace Tests\Unit\Unit\Unit\Chat;

use App\DTOs\Chat\ChatMessagesRequestDTO;
use Illuminate\Http\Request;
use Tests\TestCase;

class ChatMessagesRequestDTOTest extends TestCase
{
    /** @test */
    public function from_array_creates_dto_with_provided_values(): void
    {
        // Arrange
        $data = [
            'id' => 123,
            'limit' => 50,
            'offset' => 10,
            'page' => 2,
            'page_size' => 25,
            'list' => 'recent',
            'count' => 100,
        ];

        // Act
        $dto = ChatMessagesRequestDTO::fromArray($data);

        // Assert
        $this->assertEquals(123, $dto->conversationId);
        $this->assertEquals(50, $dto->limit);
        $this->assertEquals(10, $dto->offset);
        $this->assertEquals(2, $dto->page);
        $this->assertEquals(25, $dto->pageSize);
        $this->assertEquals('recent', $dto->list);
        $this->assertEquals(100, $dto->count);
    }

    /** @test */
    public function from_array_uses_default_values_when_missing(): void
    {
        // Arrange
        $data = ['id' => 123];

        // Act
        $dto = ChatMessagesRequestDTO::fromArray($data);

        // Assert
        $this->assertEquals(123, $dto->conversationId);
        $this->assertEquals(20, $dto->limit);
        $this->assertEquals(0, $dto->offset);
        $this->assertNull($dto->page);
        $this->assertNull($dto->pageSize);
        $this->assertNull($dto->list);
        $this->assertNull($dto->count);
    }

    /** @test */
    public function from_request_creates_dto_from_request_data(): void
    {
        // Arrange
        $request = new Request([
            'id' => 456,
            'limit' => 30,
            'offset' => 5,
            'page' => 3,
            'page_size' => 15,
            'list' => 'all',
            'count' => 50,
        ]);

        // Act
        $dto = ChatMessagesRequestDTO::fromRequest($request);

        // Assert
        $this->assertEquals(456, $dto->conversationId);
        $this->assertEquals(30, $dto->limit);
        $this->assertEquals(5, $dto->offset);
        $this->assertEquals(3, $dto->page);
        $this->assertEquals(15, $dto->pageSize);
        $this->assertEquals('all', $dto->list);
        $this->assertEquals(50, $dto->count);
    }

    /** @test */
    public function from_request_uses_default_values_when_missing(): void
    {
        // Arrange
        $request = new Request(['id' => 789]);

        // Act
        $dto = ChatMessagesRequestDTO::fromRequest($request);

        // Assert
        $this->assertEquals(789, $dto->conversationId);
        $this->assertEquals(20, $dto->limit);
        $this->assertEquals(0, $dto->offset);
        $this->assertNull($dto->page);
        $this->assertNull($dto->pageSize);
        $this->assertNull($dto->list);
        $this->assertNull($dto->count);
    }

    /** @test */
    public function to_array_returns_correct_structure(): void
    {
        // Arrange
        $dto = new ChatMessagesRequestDTO(
            conversationId: 123,
            limit: 25,
            offset: 15,
            page: 2,
            pageSize: 20,
            list: 'recent',
            count: 100
        );

        // Act
        $array = $dto->toArray();

        // Assert
        $this->assertIsArray($array);
        $this->assertEquals(123, $array['id']);
        $this->assertEquals(25, $array['limit']);
        $this->assertEquals(15, $array['offset']);
        $this->assertEquals(2, $array['page']);
        $this->assertEquals(20, $array['page_size']);
        $this->assertEquals('recent', $array['list']);
        $this->assertEquals(100, $array['count']);
    }

    /** @test */
    public function validate_returns_true_for_valid_data(): void
    {
        // Arrange
        $dto = new ChatMessagesRequestDTO(
            conversationId: 123,
            limit: 20,
            offset: 0
        );

        // Act
        $isValid = $dto->validate();

        // Assert
        $this->assertTrue($isValid);
    }

    /** @test */
    public function validate_returns_false_for_invalid_conversation_id(): void
    {
        // Arrange
        $dto = new ChatMessagesRequestDTO(
            conversationId: 0,
            limit: 20,
            offset: 0
        );

        // Act
        $isValid = $dto->validate();

        // Assert
        $this->assertFalse($isValid);
    }

    /** @test */
    public function validate_returns_false_for_invalid_limit(): void
    {
        // Arrange
        $dto = new ChatMessagesRequestDTO(
            conversationId: 123,
            limit: 0,
            offset: 0
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
        $dto = new ChatMessagesRequestDTO(
            conversationId: 123,
            limit: 20,
            offset: -1
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
        $dto = new ChatMessagesRequestDTO(
            conversationId: 123,
            limit: 20,
            offset: 10
        );

        // Act
        $isValid = $dto->validate();

        // Assert
        $this->assertTrue($isValid);
    }
}