<?php

declare(strict_types=1);

namespace Tests\Unit\Unit\Authentication;

use App\Domain\DTOs\Authentication\AuthResponseDTO;
use App\Domain\DTOs\Authentication\LoginRequestDTO;
use App\Domain\Services\Authentication\AuthService;
use App\Models\Device\Device;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    /**
     * Test successful authentication with valid credentials
     */
    public function test_authenticate_with_valid_credentials_returns_auth_response(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $loginDto = new LoginRequestDTO(
            email: 'test@example.com',
            password: 'password123',
            device: [
                'device_uuid' => 'test-uuid-123',
                'device_os' => 'iOS',
            ]
        );

        // Act
        $result = $this->authService->authenticate($loginDto);

        // Assert
        $this->assertInstanceOf(AuthResponseDTO::class, $result);
        $this->assertEquals($user->id, $result->user_id);
        $this->assertNotEmpty($result->access_token);
        $this->assertEquals(3600, $result->expires_in);
        $this->assertIsArray($result->user_profile);
        $this->assertEquals($user->email, $result->user_profile['email']);
        $this->assertEquals($user->first_name, $result->user_profile['first_name']);
        $this->assertEquals($user->last_name, $result->user_profile['last_name']);
        $this->assertEquals($user->role, $result->user_profile['role']);

        // Verify device record was created
        $device = Device::where('id_user', $user->id)->first();
        $this->assertNotNull($device);
        $this->assertEquals($result->access_token, $device->access_token);
        $this->assertEquals('test-uuid-123', $device->uuid);
        $this->assertEquals('iOS', $device->os);
    }

    /**
     * Test authentication fails with invalid email
     */
    public function test_authenticate_with_invalid_email_returns_null(): void
    {
        // Arrange
        User::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $loginDto = new LoginRequestDTO(
            email: 'wrong@example.com',
            password: 'password123',
            device: ['device_uuid' => 'test-uuid']
        );

        // Act
        $result = $this->authService->authenticate($loginDto);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test authentication fails with invalid password
     */
    public function test_authenticate_with_invalid_password_returns_null(): void
    {
        // Arrange
        User::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $loginDto = new LoginRequestDTO(
            email: 'test@example.com',
            password: 'wrongpassword',
            device: ['device_uuid' => 'test-uuid']
        );

        // Act
        $result = $this->authService->authenticate($loginDto);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test device record is updated on subsequent logins
     */
    public function test_authenticate_updates_existing_device_record(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        // Create existing device record
        $existingDevice = Device::factory()->create([
            'id_user' => $user->id,
            'access_token' => 'old-token',
            'uuid' => 'old-uuid',
            'os' => 'Android',
        ]);

        $loginDto = new LoginRequestDTO(
            email: 'test@example.com',
            password: 'password123',
            device: [
                'device_uuid' => 'new-uuid',
                'device_os' => 'iOS',
            ]
        );

        // Act
        $result = $this->authService->authenticate($loginDto);

        // Assert
        $this->assertInstanceOf(AuthResponseDTO::class, $result);

        // Verify device was updated
        $existingDevice->refresh();
        $this->assertEquals($result->access_token, $existingDevice->access_token);
        $this->assertEquals('new-uuid', $existingDevice->uuid);
        $this->assertEquals('iOS', $existingDevice->os);
    }

    /**
     * Test authentication with missing device info uses defaults
     */
    public function test_authenticate_with_missing_device_info_uses_defaults(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $loginDto = new LoginRequestDTO(
            email: 'test@example.com',
            password: 'password123',
            device: []
        );

        // Act
        $result = $this->authService->authenticate($loginDto);

        // Assert
        $this->assertInstanceOf(AuthResponseDTO::class, $result);

        $device = Device::where('id_user', $user->id)->first();
        $this->assertEquals('unknown', $device->uuid);
        $this->assertEquals('unknown', $device->os);
    }

    /**
     * Test logout removes device record
     */
    public function test_logout_removes_device_record(): void
    {
        // Arrange
        $user = User::factory()->create();
        $device = Device::factory()->create([
            'id_user' => $user->id,
            'access_token' => 'test-token-123',
        ]);

        // Create Sanctum token
        $token = $user->createToken('test-token');
        $token->accessToken->token = hash('sha256', 'test-token-123');
        $token->accessToken->save();

        // Act
        $result = $this->authService->logout('test-token-123');

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('device', [
            'access_token' => 'test-token-123',
        ]);
    }

    /**
     * Test logout with non-existent token returns false
     */
    public function test_logout_with_non_existent_token_returns_false(): void
    {
        // Act
        $result = $this->authService->logout('non-existent-token');

        // Assert
        $this->assertFalse($result);
    }
}
