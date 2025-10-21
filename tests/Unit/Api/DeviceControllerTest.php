<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Domain\Actions\Device\DeleteDeviceAction;
use App\Domain\Actions\Device\GetDeviceAction;
use App\Domain\Actions\Device\GetDeviceListAction;
use App\Domain\Actions\Device\SendPushNotificationAction;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Requests\Device\DeviceListRequest;
use App\Http\Requests\Device\DevicePushRequest;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class DeviceControllerTest extends TestCase
{
    private DeviceController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new DeviceController();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_calls_get_device_list_action(): void
    {
        $request = Mockery::mock(DeviceListRequest::class);
        $action = Mockery::mock(GetDeviceListAction::class);
        $validatedData = ['page' => 1];
        $deviceObj = new class
        {
            public int $id = 1;

            public int $id_user = 10;

            public $created_at = null;

            public $updated_at = null;

            public function relationLoaded($relationship): bool
            {
                return false;
            }

            public function getAttribute($key)
            {
                $map = [
                    'device_uuid' => 'uuid-1',
                    'uuid' => 'uuid-1',
                    'device_name' => 'Test Device',
                    'os' => 'ios',
                    'os_version' => '17',
                    'app_version' => '1.0.0',
                    'push_token' => 'tok',
                    'is_active' => true,
                    'last_seen' => null,
                ];

                return $map[$key] ?? null;
            }
        };
        $devices = [$deviceObj];

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $action->shouldReceive('execute')->once()->with($validatedData)->andReturn($devices);

        $result = $this->controller->index($request, $action);

        $this->assertInstanceOf(JsonResponse::class, $result);
    }

    public function test_view_calls_get_device_action(): void
    {
        $action = Mockery::mock(GetDeviceAction::class);
        $device = new class
        {
            public int $id = 1;

            public int $id_user = 10;

            public $created_at = null;

            public $updated_at = null;

            public function relationLoaded($relationship): bool
            {
                return false;
            }

            public function getAttribute($key)
            {
                $map = [
                    'device_uuid' => 'uuid-1',
                    'uuid' => 'uuid-1',
                    'device_name' => 'Test Device',
                    'os' => 'ios',
                    'os_version' => '17',
                    'app_version' => '1.0.0',
                    'push_token' => 'tok',
                    'is_active' => true,
                    'last_seen' => null,
                ];

                return $map[$key] ?? null;
            }
        };

        $action->shouldReceive('execute')->once()->with(['id' => 1])->andReturn($device);

        $result = $this->controller->view(1, $action);

        $this->assertInstanceOf(JsonResponse::class, $result);
    }

    public function test_delete_calls_delete_device_action(): void
    {
        $action = Mockery::mock(DeleteDeviceAction::class);

        $action->shouldReceive('execute')->once()->with(['id' => 1])->andReturn(true);

        $result = $this->controller->delete(1, $action);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Device deleted successfully', $data['message']);
    }

    public function test_push_calls_send_push_notification_action(): void
    {
        $request = Mockery::mock(DevicePushRequest::class);
        $action = Mockery::mock(SendPushNotificationAction::class);
        $validatedData = ['message' => 'Test push'];
        $pushResult = ['sent' => true];

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $action->shouldReceive('execute')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['id'] === 1 && $data['message'] === 'Test push';
            }))
            ->andReturn($pushResult);

        $result = $this->controller->push(1, $request, $action);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Push notification sent successfully', $data['message']);
        $this->assertEquals($pushResult, $data['data']);
    }
}
