<?php

declare(strict_types=1);

namespace Tests\Unit\Unit\Unit\Users;

use App\DTOs\Users\UsersListRequestDTO;
use App\Models\User\User;
use App\Services\Users\UsersService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersServiceTest extends TestCase
{
    use RefreshDatabase;

    private UsersService $usersService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->usersService = new UsersService();
        // Ensure the database is migrated for each test
        $this->artisan('migrate');
    }

    /** @test */
    public function get_users_list_returns_paginated_users(): void
    {
        // Arrange
        User::factory()->count(5)->create();
        
        $dto = new UsersListRequestDTO(
            page: 1,
            pageSize: 3,
            search: null,
            role: null,
            validEmailOnly: true
        );

        // Act
        $result = $this->usersService->getUsersList($dto, null);

        // Assert
        $this->assertCount(3, $result);
        $this->assertInstanceOf(User::class, $result->first());
    }

    /** @test */
    public function get_users_list_applies_role_filter(): void
    {
        // Arrange
        User::factory()->create(['role' => 1, 'is_valid_email' => true]);
        User::factory()->create(['role' => 2, 'is_valid_email' => true]);
        User::factory()->create(['role' => 1, 'is_valid_email' => true]);
        
        $dto = new UsersListRequestDTO(
            page: 1,
            pageSize: 10,
            search: null,
            role: 1,
            validEmailOnly: true
        );

        // Act
        $result = $this->usersService->getUsersList($dto, null);

        // Assert
        $this->assertCount(2, $result);
        $this->assertEquals(1, $result->first()->role);
        $this->assertEquals(1, $result->last()->role);
    }

    /** @test */
    public function get_users_list_applies_search_filter(): void
    {
        // Arrange
        User::factory()->create(['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com', 'is_valid_email' => true]);
        User::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane@example.com', 'is_valid_email' => true]);
        User::factory()->create(['first_name' => 'Bob', 'last_name' => 'Johnson', 'email' => 'bob@example.com', 'is_valid_email' => true]);
        
        $dto = new UsersListRequestDTO(
            page: 1,
            pageSize: 10,
            search: 'John',
            role: null,
            validEmailOnly: true
        );

        // Act
        $result = $this->usersService->getUsersList($dto, null);

        // Assert
        $this->assertCount(2, $result);
        $this->assertTrue($result->contains('first_name', 'John'));
        $this->assertTrue($result->contains('last_name', 'Johnson'));
    }

    /** @test */
    public function get_users_list_applies_valid_email_filter(): void
    {
        // Arrange
        User::factory()->create(['is_valid_email' => true]);
        User::factory()->create(['is_valid_email' => false]);
        User::factory()->create(['is_valid_email' => true]);
        
        $dto = new UsersListRequestDTO(
            page: 1,
            pageSize: 10,
            search: null,
            role: null,
            validEmailOnly: true
        );

        // Act
        $result = $this->usersService->getUsersList($dto, null);

        // Assert
        $this->assertCount(2, $result);
        // Note: is_valid_email is not selected in the query, so we can't assert its value
    }

    /** @test */
    public function get_users_list_ignores_valid_email_filter_when_false(): void
    {
        // Arrange
        User::factory()->create(['is_valid_email' => true]);
        User::factory()->create(['is_valid_email' => false]);
        User::factory()->create(['is_valid_email' => true]);
        
        $dto = new UsersListRequestDTO(
            page: 1,
            pageSize: 10,
            search: null,
            role: null,
            validEmailOnly: false
        );

        // Act
        $result = $this->usersService->getUsersList($dto, null);

        // Assert
        $this->assertCount(3, $result);
    }

    /** @test */
    public function get_users_list_applies_pagination(): void
    {
        // Arrange
        User::factory()->count(10)->create(['is_valid_email' => true]);
        
        $dto = new UsersListRequestDTO(
            page: 2,
            pageSize: 3,
            search: null,
            role: null,
            validEmailOnly: true
        );

        // Act
        $result = $this->usersService->getUsersList($dto, null);

        // Assert
        $this->assertCount(3, $result);
    }

    /** @test */
    public function get_users_list_returns_empty_when_no_users(): void
    {
        // Arrange
        $dto = new UsersListRequestDTO(
            page: 1,
            pageSize: 10,
            search: null,
            role: null,
            validEmailOnly: true
        );

        // Act
        $result = $this->usersService->getUsersList($dto, null);

        // Assert
        $this->assertCount(0, $result);
    }

    /** @test */
    public function get_users_list_orders_by_first_name(): void
    {
        // Arrange
        User::factory()->create(['first_name' => 'Charlie', 'is_valid_email' => true]);
        User::factory()->create(['first_name' => 'Alice', 'is_valid_email' => true]);
        User::factory()->create(['first_name' => 'Bob', 'is_valid_email' => true]);
        
        $dto = new UsersListRequestDTO(
            page: 1,
            pageSize: 10,
            search: null,
            role: null,
            validEmailOnly: true
        );

        // Act
        $result = $this->usersService->getUsersList($dto, null);

        // Assert
        $this->assertCount(3, $result);
        $this->assertEquals('Alice', $result->first()->first_name);
        $this->assertEquals('Bob', $result->get(1)->first_name);
        $this->assertEquals('Charlie', $result->last()->first_name);
    }

    /** @test */
    public function get_users_list_returns_only_selected_columns(): void
    {
        // Arrange
        User::factory()->create();
        
        $dto = new UsersListRequestDTO(
            page: 1,
            pageSize: 1,
            search: null,
            role: null,
            validEmailOnly: true
        );

        // Act
        $result = $this->usersService->getUsersList($dto, null);

        // Assert
        $this->assertCount(1, $result);
        $user = $result->first();
        $this->assertNotNull($user);
        $this->assertArrayHasKey('id', $user->toArray());
        $this->assertArrayHasKey('email', $user->toArray());
        $this->assertArrayHasKey('first_name', $user->toArray());
        $this->assertArrayHasKey('last_name', $user->toArray());
        $this->assertArrayHasKey('role', $user->toArray());
        $this->assertArrayNotHasKey('password_hash', $user->toArray());
    }
}