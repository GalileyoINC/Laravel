<?php

declare(strict_types=1);

namespace Tests\Unit\Unit\Unit\Users;

use App\DTOs\Users\UsersListRequestDTO;
use Illuminate\Http\Request;
use Tests\TestCase;

class UsersListRequestDTOTest extends TestCase
{
    /** @test */
    public function from_array_creates_dto_with_provided_values(): void
    {
        // Arrange
        $data = [
            'page' => 2,
            'page_size' => 25,
            'search' => 'john',
            'role' => 1,
            'valid_email_only' => false,
        ];

        // Act
        $dto = UsersListRequestDTO::fromArray($data);

        // Assert
        $this->assertEquals(2, $dto->page);
        $this->assertEquals(25, $dto->pageSize);
        $this->assertEquals('john', $dto->search);
        $this->assertEquals(1, $dto->role);
        $this->assertFalse($dto->validEmailOnly);
    }

    /** @test */
    public function from_array_uses_default_values_when_missing(): void
    {
        // Arrange
        $data = [];

        // Act
        $dto = UsersListRequestDTO::fromArray($data);

        // Assert
        $this->assertEquals(1, $dto->page);
        $this->assertEquals(50, $dto->pageSize);
        $this->assertNull($dto->search);
        $this->assertNull($dto->role);
        $this->assertTrue($dto->validEmailOnly);
    }

    /** @test */
    public function from_request_creates_dto_from_request_data(): void
    {
        // Arrange
        $request = new Request([
            'page' => 3,
            'page_size' => 20,
            'search' => 'jane',
            'role' => 2,
            'valid_email_only' => true,
        ]);

        // Act
        $dto = UsersListRequestDTO::fromRequest($request);

        // Assert
        $this->assertEquals(3, $dto->page);
        $this->assertEquals(20, $dto->pageSize);
        $this->assertEquals('jane', $dto->search);
        $this->assertEquals(2, $dto->role);
        $this->assertTrue($dto->validEmailOnly);
    }

    /** @test */
    public function from_request_uses_default_values_when_missing(): void
    {
        // Arrange
        $request = new Request();

        // Act
        $dto = UsersListRequestDTO::fromRequest($request);

        // Assert
        $this->assertEquals(1, $dto->page);
        $this->assertEquals(50, $dto->pageSize);
        $this->assertNull($dto->search);
        $this->assertNull($dto->role);
        $this->assertTrue($dto->validEmailOnly);
    }

    /** @test */
    public function to_array_returns_correct_structure(): void
    {
        // Arrange
        $dto = new UsersListRequestDTO(
            page: 2,
            pageSize: 25,
            search: 'test',
            role: 1,
            validEmailOnly: false
        );

        // Act
        $array = $dto->toArray();

        // Assert
        $this->assertIsArray($array);
        $this->assertEquals(2, $array['page']);
        $this->assertEquals(25, $array['page_size']);
        $this->assertEquals('test', $array['search']);
        $this->assertEquals(1, $array['role']);
        $this->assertFalse($array['valid_email_only']);
    }

    /** @test */
    public function validate_returns_true_for_valid_data(): void
    {
        // Arrange
        $dto = new UsersListRequestDTO(
            page: 1,
            pageSize: 50,
            search: null,
            role: null,
            validEmailOnly: true
        );

        // Act
        $isValid = $dto->validate();

        // Assert
        $this->assertTrue($isValid);
    }

    /** @test */
    public function validate_returns_false_for_invalid_page(): void
    {
        // Arrange
        $dto = new UsersListRequestDTO(
            page: 0,
            pageSize: 50,
            search: null,
            role: null,
            validEmailOnly: true
        );

        // Act
        $isValid = $dto->validate();

        // Assert
        $this->assertFalse($isValid);
    }

    /** @test */
    public function validate_returns_false_for_invalid_page_size(): void
    {
        // Arrange
        $dto = new UsersListRequestDTO(
            page: 1,
            pageSize: 0,
            search: null,
            role: null,
            validEmailOnly: true
        );

        // Act
        $isValid = $dto->validate();

        // Assert
        $this->assertFalse($isValid);
    }

    /** @test */
    public function validate_returns_false_for_page_size_too_large(): void
    {
        // Arrange
        $dto = new UsersListRequestDTO(
            page: 1,
            pageSize: 101,
            search: null,
            role: null,
            validEmailOnly: true
        );

        // Act
        $isValid = $dto->validate();

        // Assert
        $this->assertFalse($isValid);
    }

    /** @test */
    public function validate_returns_true_for_maximum_page_size(): void
    {
        // Arrange
        $dto = new UsersListRequestDTO(
            page: 1,
            pageSize: 100,
            search: null,
            role: null,
            validEmailOnly: true
        );

        // Act
        $isValid = $dto->validate();

        // Assert
        $this->assertTrue($isValid);
    }
}