<?php

declare(strict_types=1);

namespace Tests\Feature\Feature\Authentication;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test web login endpoint with valid credentials
     */
    public function test_web_login_with_valid_credentials_returns_success(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
            'first_name' => 'John',
            'last_name' => 'Doe',
            'role' => 1,
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        // Act
        $response = $this->postJson('/api/auth/login', $loginData);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'user_id' => $user->id,
            ])
            ->assertJsonStructure([
                'status',
                'user_id',
                'access_token',
                'user_profile' => [
                    'id',
                    'email',
                    'first_name',
                    'last_name',
                    'role',
                ],
            ]);

        $responseData = $response->json();
        $this->assertNotEmpty($responseData['access_token']);
        $this->assertEquals($user->email, $responseData['user_profile']['email']);
        $this->assertEquals($user->first_name, $responseData['user_profile']['first_name']);
        $this->assertEquals($user->last_name, $responseData['user_profile']['last_name']);
        $this->assertEquals($user->role, $responseData['user_profile']['role']);
    }

    /**
     * Test web login endpoint with invalid email
     */
    public function test_web_login_with_invalid_email_returns_error(): void
    {
        // Arrange
        User::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'wrong@example.com',
            'password' => 'password123',
        ];

        // Act
        $response = $this->postJson('/api/auth/login', $loginData);

        // Assert
        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Invalid credentials',
            ]);
    }

    /**
     * Test web login endpoint with invalid password
     */
    public function test_web_login_with_invalid_password_returns_error(): void
    {
        // Arrange
        User::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ];

        // Act
        $response = $this->postJson('/api/auth/login', $loginData);

        // Assert
        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Invalid credentials',
            ]);
    }

    /**
     * Test web login endpoint with missing email
     */
    public function test_web_login_with_missing_email_returns_validation_error(): void
    {
        // Arrange
        $loginData = [
            'password' => 'password123',
        ];

        // Act
        $response = $this->postJson('/api/auth/login', $loginData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test web login endpoint with missing password
     */
    public function test_web_login_with_missing_password_returns_validation_error(): void
    {
        // Arrange
        $loginData = [
            'email' => 'test@example.com',
        ];

        // Act
        $response = $this->postJson('/api/auth/login', $loginData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test web login endpoint with invalid email format
     */
    public function test_web_login_with_invalid_email_format_returns_validation_error(): void
    {
        // Arrange
        $loginData = [
            'email' => 'invalid-email',
            'password' => 'password123',
        ];

        // Act
        $response = $this->postJson('/api/auth/login', $loginData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test auth test endpoint returns success
     */
    public function test_auth_test_endpoint_returns_success(): void
    {
        // Act
        $response = $this->getJson('/api/v1/auth/test');

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Authentication module is working!',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'message',
                    'time',
                    'module',
                    'version',
                ],
            ]);
    }

    /**
     * Test signup endpoint returns placeholder message
     */
    public function test_signup_endpoint_returns_placeholder(): void
    {
        // Act
        $response = $this->postJson('/api/v1/default/signup', []);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Signup endpoint - to be implemented',
            ]);
    }

    /**
     * Test news by subscription endpoint returns placeholder
     */
    public function test_news_by_subscription_endpoint_returns_placeholder(): void
    {
        // Act
        $response = $this->postJson('/api/v1/default/news-by-subscription', []);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'News by subscription endpoint - to be implemented',
            ]);
    }

    /**
     * Test login creates Sanctum token
     */
    public function test_web_login_creates_sanctum_token(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        // Act
        $response = $this->postJson('/api/auth/login', $loginData);

        // Assert
        $response->assertStatus(200);
        
        $responseData = $response->json();
        $this->assertNotEmpty($responseData['access_token']);
        
        // Verify token exists in database
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);
    }
}