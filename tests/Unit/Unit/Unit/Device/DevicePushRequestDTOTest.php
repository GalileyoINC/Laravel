<?php

declare(strict_types=1);

namespace Tests\Unit\Unit\Unit\Device;

use App\DTOs\Device\DevicePushRequestDTO;
use Tests\TestCase;

class DevicePushRequestDTOTest extends TestCase
{
    /** @test */
    public function constructor_creates_dto_with_provided_values(): void
    {
        // Arrange & Act
        $dto = new DevicePushRequestDTO(
            id: 123,
            title: 'Test Notification',
            body: 'This is a test message',
            data: ['key' => 'value', 'number' => 42],
            sound: 'default',
            badge: 5
        );

        // Assert
        $this->assertEquals(123, $dto->id);
        $this->assertEquals('Test Notification', $dto->title);
        $this->assertEquals('This is a test message', $dto->body);
        $this->assertEquals(['key' => 'value', 'number' => 42], $dto->data);
        $this->assertEquals('default', $dto->sound);
        $this->assertEquals(5, $dto->badge);
    }

    /** @test */
    public function constructor_accepts_null_values(): void
    {
        // Arrange & Act
        $dto = new DevicePushRequestDTO(
            id: 456,
            title: 'Simple Notification',
            body: 'Basic message',
            data: null,
            sound: 'custom',
            badge: null
        );

        // Assert
        $this->assertEquals(456, $dto->id);
        $this->assertEquals('Simple Notification', $dto->title);
        $this->assertEquals('Basic message', $dto->body);
        $this->assertNull($dto->data);
        $this->assertEquals('custom', $dto->sound);
        $this->assertNull($dto->badge);
    }

    /** @test */
    public function constructor_accepts_empty_string_values(): void
    {
        // Arrange & Act
        $dto = new DevicePushRequestDTO(
            id: 789,
            title: '',
            body: '',
            data: null,
            sound: '',
            badge: null
        );

        // Assert
        $this->assertEquals(789, $dto->id);
        $this->assertEquals('', $dto->title);
        $this->assertEquals('', $dto->body);
        $this->assertNull($dto->data);
        $this->assertEquals('', $dto->sound);
        $this->assertNull($dto->badge);
    }

    /** @test */
    public function constructor_accepts_zero_values(): void
    {
        // Arrange & Act
        $dto = new DevicePushRequestDTO(
            id: 0,
            title: 'Zero ID Test',
            body: 'Testing zero ID',
            data: null,
            sound: 'default',
            badge: 0
        );

        // Assert
        $this->assertEquals(0, $dto->id);
        $this->assertEquals('Zero ID Test', $dto->title);
        $this->assertEquals('Testing zero ID', $dto->body);
        $this->assertNull($dto->data);
        $this->assertEquals('default', $dto->sound);
        $this->assertEquals(0, $dto->badge);
    }

    /** @test */
    public function constructor_accepts_negative_values(): void
    {
        // Arrange & Act
        $dto = new DevicePushRequestDTO(
            id: -1,
            title: 'Negative ID Test',
            body: 'Testing negative ID',
            data: null,
            sound: 'default',
            badge: -5
        );

        // Assert
        $this->assertEquals(-1, $dto->id);
        $this->assertEquals('Negative ID Test', $dto->title);
        $this->assertEquals('Testing negative ID', $dto->body);
        $this->assertNull($dto->data);
        $this->assertEquals('default', $dto->sound);
        $this->assertEquals(-5, $dto->badge);
    }

    /** @test */
    public function constructor_accepts_large_values(): void
    {
        // Arrange & Act
        $dto = new DevicePushRequestDTO(
            id: 999999,
            title: 'Very Long Notification Title That Exceeds Normal Length',
            body: 'This is a very long notification body that contains a lot of text and should test the limits of the DTO constructor',
            data: ['very_long_key' => 'very_long_value', 'array' => [1, 2, 3, 4, 5]],
            sound: 'very-long-custom-sound-name',
            badge: 999999
        );

        // Assert
        $this->assertEquals(999999, $dto->id);
        $this->assertEquals('Very Long Notification Title That Exceeds Normal Length', $dto->title);
        $this->assertEquals('This is a very long notification body that contains a lot of text and should test the limits of the DTO constructor', $dto->body);
        $this->assertEquals(['very_long_key' => 'very_long_value', 'array' => [1, 2, 3, 4, 5]], $dto->data);
        $this->assertEquals('very-long-custom-sound-name', $dto->sound);
        $this->assertEquals(999999, $dto->badge);
    }

    /** @test */
    public function constructor_accepts_special_characters(): void
    {
        // Arrange & Act
        $dto = new DevicePushRequestDTO(
            id: 123,
            title: 'Special Chars: !@#$%^&*()_+-=[]{}|;:,.<>?',
            body: 'Unicode: 🚀📱💻🔥',
            data: ['special' => '!@#$%^&*()', 'unicode' => '🚀📱💻'],
            sound: 'special-sound!@#',
            badge: 42
        );

        // Assert
        $this->assertEquals(123, $dto->id);
        $this->assertEquals('Special Chars: !@#$%^&*()_+-=[]{}|;:,.<>?', $dto->title);
        $this->assertEquals('Unicode: 🚀📱💻🔥', $dto->body);
        $this->assertEquals(['special' => '!@#$%^&*()', 'unicode' => '🚀📱💻'], $dto->data);
        $this->assertEquals('special-sound!@#', $dto->sound);
        $this->assertEquals(42, $dto->badge);
    }
}