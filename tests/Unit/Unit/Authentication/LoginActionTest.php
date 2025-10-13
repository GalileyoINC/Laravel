<?php

declare(strict_types=1);

namespace Tests\Unit\Unit\Authentication;

use App\Actions\Authentication\LoginAction;
use App\DTOs\Authentication\AuthResponseDTO;
use App\DTOs\Authentication\LoginRequestDTO;
use App\Services\Authentication\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class LoginActionTest extends TestCase
{
    private LoginAction $loginAction;
    private AuthServiceInterface $mockAuthService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockAuthService = $this->createMock(AuthServiceInterface::class);
        $this->loginAction = new LoginAction($this->mockAuthService);
    }

    /**
     * Test successful login returns proper JSON response
     */
    public function test_execute_with_valid_credentials_returns_success_response(): void
    {
        // Arrange
        $authResponse = new AuthResponseDTO(
            user_id: 123,
            access_token: 'test-token-123',
            refresh_token: 'refresh-token-123',
            expires_in: 3600,
            user_profile: [
                'id' => 123,
                'email' => 'test@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'role' => 1,
            ]
        );

        $this->mockAuthService
            ->expects($this->once())
            ->method('authenticate')
            ->willReturn($authResponse);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'device' => [
                'device_uuid' => 'test-uuid',
                'device_os' => 'iOS',
            ],
        ];

        // Act
        $result = $this->loginAction->execute($loginData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
        
        $responseData = $result->getData(true);
        $this->assertEquals(123, $responseData['user_id']);
        $this->assertEquals('test-token-123', $responseData['access_token']);
        $this->assertEquals('refresh-token-123', $responseData['refresh_token']);
        $this->assertEquals(3600, $responseData['expires_in']);
        $this->assertIsArray($responseData['user_profile']);
        $this->assertEquals('test@example.com', $responseData['user_profile']['email']);
    }

    /**
     * Test login with invalid credentials returns error response
     */
    public function test_execute_with_invalid_credentials_returns_error_response(): void
    {
        // Arrange
        $this->mockAuthService
            ->expects($this->once())
            ->method('authenticate')
            ->willReturn(null);

        $loginData = [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
            'device' => ['device_uuid' => 'test-uuid'],
        ];

        // Act
        $result = $this->loginAction->execute($loginData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(401, $result->getStatusCode());
        
        $responseData = $result->getData(true);
        $this->assertEquals('Invalid credentials', $responseData['error']);
        $this->assertEquals(401, $responseData['code']);
    }

    /**
     * Test login with exception returns error response
     */
    public function test_execute_with_exception_returns_error_response(): void
    {
        // Arrange
        $this->mockAuthService
            ->expects($this->once())
            ->method('authenticate')
            ->willThrowException(new \Exception('Database connection failed'));

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'device' => ['device_uuid' => 'test-uuid'],
        ];

        // Act
        $result = $this->loginAction->execute($loginData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(500, $result->getStatusCode());
        
        $responseData = $result->getData(true);
        $this->assertEquals('Login failed', $responseData['error']);
        $this->assertEquals(500, $responseData['code']);
    }

    /**
     * Test login with missing required fields
     */
    public function test_execute_with_missing_email_handles_gracefully(): void
    {
        // Arrange
        $this->mockAuthService
            ->expects($this->once())
            ->method('authenticate')
            ->willReturn(null);

        $loginData = [
            'password' => 'password123',
            'device' => ['device_uuid' => 'test-uuid'],
        ];

        // Act
        $result = $this->loginAction->execute($loginData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(401, $result->getStatusCode());
        
        $responseData = $result->getData(true);
        $this->assertEquals('Invalid credentials', $responseData['error']);
        $this->assertEquals(401, $responseData['code']);
    }

    /**
     * Test login with empty device info
     */
    public function test_execute_with_empty_device_info_works(): void
    {
        // Arrange
        $authResponse = new AuthResponseDTO(
            user_id: 123,
            access_token: 'test-token-123',
            refresh_token: '',
            expires_in: 3600,
            user_profile: [
                'id' => 123,
                'email' => 'test@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'role' => 1,
            ]
        );

        $this->mockAuthService
            ->expects($this->once())
            ->method('authenticate')
            ->willReturn($authResponse);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'device' => [],
        ];

        // Act
        $result = $this->loginAction->execute($loginData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }
}