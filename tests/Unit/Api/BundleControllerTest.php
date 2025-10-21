<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Domain\Actions\Bundle\CreateBundleAction;
use App\Domain\Actions\Bundle\GetBundleDeviceDataAction;
use App\Domain\Actions\Bundle\GetBundleListAction;
use App\Domain\Actions\Bundle\UpdateBundleAction;
use App\Http\Controllers\Api\BundleController;
use App\Http\Requests\Bundle\BundleCreateRequest;
use App\Http\Requests\Bundle\BundleDeviceDataRequest;
use App\Http\Requests\Bundle\BundleListRequest;
use App\Http\Requests\Bundle\BundleUpdateRequest;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class BundleControllerTest extends TestCase
{
    private BundleController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new BundleController();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_calls_get_bundle_list_action(): void
    {
        $request = Mockery::mock(BundleListRequest::class);
        $action = Mockery::mock(GetBundleListAction::class);
        $validatedData = ['page' => 1];
        $bundleObj = new class
        {
            public function relationLoaded($relationship): bool
            {
                return false;
            }

            public function getAttribute($key)
            {
                $map = [
                    'id' => 1,
                    'name' => 'Test Bundle',
                    'description' => null,
                    'price' => 9.99,
                    'is_active' => true,
                    'created_at' => null,
                    'updated_at' => null,
                ];

                return $map[$key] ?? null;
            }
        };
        $bundles = [$bundleObj];

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $action->shouldReceive('execute')->once()->with($validatedData)->andReturn($bundles);

        $result = $this->controller->index($request, $action);

        $this->assertInstanceOf(JsonResponse::class, $result);
    }

    public function test_create_calls_create_bundle_action(): void
    {
        $request = Mockery::mock(BundleCreateRequest::class);
        $action = Mockery::mock(CreateBundleAction::class);
        $validatedData = ['name' => 'New Bundle'];
        $bundle = new class
        {
            public function relationLoaded($relationship): bool
            {
                return false;
            }

            public function getAttribute($key)
            {
                $map = [
                    'id' => 1,
                    'name' => 'New Bundle',
                    'description' => null,
                    'price' => 9.99,
                    'is_active' => true,
                    'created_at' => null,
                    'updated_at' => null,
                ];

                return $map[$key] ?? null;
            }
        };

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $action->shouldReceive('execute')->once()->with($validatedData)->andReturn($bundle);

        $result = $this->controller->create($request, $action);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(201, $result->getStatusCode());
    }

    public function test_update_calls_update_bundle_action(): void
    {
        $request = Mockery::mock(BundleUpdateRequest::class);
        $action = Mockery::mock(UpdateBundleAction::class);
        $validatedData = ['name' => 'Updated Bundle'];
        $bundle = new class
        {
            public function relationLoaded($relationship): bool
            {
                return false;
            }

            public function getAttribute($key)
            {
                $map = [
                    'id' => 1,
                    'name' => 'Updated Bundle',
                    'description' => null,
                    'price' => 9.99,
                    'is_active' => true,
                    'created_at' => null,
                    'updated_at' => null,
                ];

                return $map[$key] ?? null;
            }
        };

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $action->shouldReceive('execute')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['id'] === 1 && $data['name'] === 'Updated Bundle';
            }))
            ->andReturn($bundle);

        $result = $this->controller->update(1, $request, $action);

        $this->assertInstanceOf(JsonResponse::class, $result);
    }

    public function test_device_data_calls_get_bundle_device_data_action(): void
    {
        $request = Mockery::mock(BundleDeviceDataRequest::class);
        $action = Mockery::mock(GetBundleDeviceDataAction::class);
        $validatedData = ['bundle_id' => 1];
        $deviceData = ['devices' => []];

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $action->shouldReceive('execute')->once()->with($validatedData)->andReturn($deviceData);

        $result = $this->controller->deviceData($request, $action);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($deviceData, $result->getData(true));
    }
}
