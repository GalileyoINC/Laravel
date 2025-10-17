<?php

declare(strict_types=1);

namespace Tests\Unit\Unit\Unit\Device;

use App\Domain\DTOs\Device\DeviceListRequestDTO;
use App\Domain\DTOs\Device\DevicePushRequestDTO;
use App\Domain\Services\Device\DeviceService;
use App\Models\Device\Device;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class DeviceServiceTest extends TestCase
{
    use RefreshDatabase;

    private DeviceService $deviceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->deviceService = new DeviceService();
        // Ensure the database is migrated for each test
        $this->artisan('migrate');
    }

    /** @test */
    public function get_list_returns_paginated_devices(): void
    {
        // Arrange
        Device::factory()->count(5)->create();

        $dto = new DeviceListRequestDTO(
            page: 1,
            limit: 3,
            search: null,
            user_id: null,
            os: null
        );

        // Act
        $result = $this->deviceService->getList($dto);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertCount(3, $result['data']);
        $this->assertEquals(1, $result['pagination']['current_page']);
        $this->assertEquals(2, $result['pagination']['last_page']);
        $this->assertEquals(3, $result['pagination']['per_page']);
        $this->assertEquals(5, $result['pagination']['total']);
    }

    /** @test */
    public function get_list_applies_search_filter(): void
    {
        // Arrange
        $user = User::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
        Device::factory()->create([
            'uuid' => 'test-device-123',
            'os' => 'ios',
            'id_user' => $user->id,
        ]);
        Device::factory()->create([
            'uuid' => 'other-device-456',
            'os' => 'android',
        ]);

        $dto = new DeviceListRequestDTO(
            page: 1,
            limit: 10,
            search: 'ios',
            user_id: null,
            os: null
        );

        // Act
        $result = $this->deviceService->getList($dto);

        // Assert
        $this->assertCount(1, $result['data']);
        $this->assertEquals('ios', $result['data'][0]->os);
    }

    /** @test */
    public function get_list_applies_user_id_filter(): void
    {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Device::factory()->create(['id_user' => $user1->id]);
        Device::factory()->create(['id_user' => $user2->id]);
        Device::factory()->create(['id_user' => $user1->id]);

        $dto = new DeviceListRequestDTO(
            page: 1,
            limit: 10,
            search: null,
            user_id: $user1->id,
            os: null
        );

        // Act
        $result = $this->deviceService->getList($dto);

        // Assert
        $this->assertCount(2, $result['data']);
        $this->assertEquals($user1->id, $result['data'][0]->id_user);
        $this->assertEquals($user1->id, $result['data'][1]->id_user);
    }

    /** @test */
    public function get_list_applies_os_filter(): void
    {
        // Arrange
        Device::factory()->create(['os' => 'ios']);
        Device::factory()->create(['os' => 'android']);
        Device::factory()->create(['os' => 'ios']);

        $dto = new DeviceListRequestDTO(
            page: 1,
            limit: 10,
            search: null,
            user_id: null,
            os: 'ios'
        );

        // Act
        $result = $this->deviceService->getList($dto);

        // Assert
        $this->assertCount(2, $result['data']);
        $this->assertEquals('ios', $result['data'][0]->os);
        $this->assertEquals('ios', $result['data'][1]->os);
    }

    /** @test */
    public function get_list_searches_by_user_name_and_email(): void
    {
        // Arrange
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);
        Device::factory()->create(['id_user' => $user->id]);

        $dto = new DeviceListRequestDTO(
            page: 1,
            limit: 10,
            search: 'John',
            user_id: null,
            os: null
        );

        // Act
        $result = $this->deviceService->getList($dto);

        // Assert
        $this->assertCount(1, $result['data']);
        $this->assertEquals($user->id, $result['data'][0]->id_user);
    }

    /** @test */
    public function get_by_id_returns_device_with_user(): void
    {
        // Arrange
        $user = User::factory()->create();
        $device = Device::factory()->create(['id_user' => $user->id]);

        // Act
        $result = $this->deviceService->getById($device->id);

        // Assert
        $this->assertInstanceOf(Device::class, $result);
        $this->assertEquals($device->id, $result->id);
        $this->assertTrue($result->relationLoaded('user'));
        $this->assertEquals($user->id, $result->user->id);
    }

    /** @test */
    public function get_by_id_throws_exception_for_non_existent_device(): void
    {
        // Act & Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->deviceService->getById(999);
    }

    /** @test */
    public function delete_removes_device(): void
    {
        // Arrange
        $device = Device::factory()->create();

        // Act
        $this->deviceService->delete($device->id);

        // Assert
        $this->assertDatabaseMissing('device', ['id' => $device->id]);
    }

    /** @test */
    public function delete_throws_exception_for_non_existent_device(): void
    {
        // Act & Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->deviceService->delete(999);
    }

    /** @test */
    public function send_push_notification_returns_notification_data(): void
    {
        // Arrange
        $device = Device::factory()->create([
            'uuid' => 'test-uuid-123',
        ]);

        $dto = new DevicePushRequestDTO(
            id: $device->id,
            title: 'Test Notification',
            body: 'This is a test message',
            data: ['key' => 'value'],
            sound: 'default',
            badge: 1
        );

        // Mock Log facade
        Log::shouldReceive('info')
            ->once()
            ->with('Push notification sent', Mockery::type('array'));

        // Act
        $result = $this->deviceService->sendPushNotification($dto);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals($device->id, $result['device_id']);
        $this->assertEquals('test-uuid-123', $result['device_uuid']);
        $this->assertEquals('Test Notification', $result['title']);
        $this->assertEquals('This is a test message', $result['body']);
        $this->assertEquals(['key' => 'value'], $result['data']);
        $this->assertEquals('default', $result['sound']);
        $this->assertEquals(1, $result['badge']);
        $this->assertEquals('sent', $result['status']);
        $this->assertArrayHasKey('sent_at', $result);
    }

    /** @test */
    public function send_push_notification_with_minimal_data(): void
    {
        // Arrange
        $device = Device::factory()->create();

        $dto = new DevicePushRequestDTO(
            id: $device->id,
            title: 'Simple Notification',
            body: 'Basic message',
            data: null,
            sound: 'default',
            badge: null
        );

        // Mock Log facade
        Log::shouldReceive('info')
            ->once()
            ->with('Push notification sent', Mockery::type('array'));

        // Act
        $result = $this->deviceService->sendPushNotification($dto);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals($device->id, $result['device_id']);
        $this->assertEquals('Simple Notification', $result['title']);
        $this->assertEquals('Basic message', $result['body']);
        $this->assertNull($result['data']);
        $this->assertEquals('default', $result['sound']);
        $this->assertNull($result['badge']);
        $this->assertEquals('sent', $result['status']);
    }

    /** @test */
    public function send_push_notification_throws_exception_for_non_existent_device(): void
    {
        // Arrange
        $dto = new DevicePushRequestDTO(
            id: 999,
            title: 'Test',
            body: 'Test',
            data: null,
            sound: 'default',
            badge: null
        );

        // Act & Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->deviceService->sendPushNotification($dto);
    }
}
