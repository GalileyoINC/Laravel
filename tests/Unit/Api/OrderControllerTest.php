<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Domain\Actions\Order\CreateOrderAction;
use App\Domain\Actions\Order\PayOrderAction;
use App\Domain\Services\Order\OrderServiceInterface;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    private OrderController $controller;

    private CreateOrderAction $createOrderAction;

    private PayOrderAction $payOrderAction;

    private OrderServiceInterface $orderService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createOrderAction = Mockery::mock(CreateOrderAction::class);
        $this->payOrderAction = Mockery::mock(PayOrderAction::class);
        $this->orderService = Mockery::mock(OrderServiceInterface::class);

        $this->controller = new OrderController(
            $this->createOrderAction,
            $this->payOrderAction,
            $this->orderService
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_calls_create_order_action(): void
    {
        $request = new Request(['product_id' => 1, 'quantity' => 2]);
        $expectedResponse = new JsonResponse(['order_id' => 123]);

        $this->createOrderAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->create($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_pay_calls_pay_order_action(): void
    {
        $request = new Request(['order_id' => 123, 'payment_method' => 'credit_card']);
        $expectedResponse = new JsonResponse(['status' => 'paid']);

        $this->payOrderAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->pay($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_test_calls_order_service(): void
    {
        $expectedTestData = ['test_order' => ['id' => 1, 'status' => 'test']];

        $this->orderService->shouldReceive('getTestOrder')
            ->once()
            ->andReturn($expectedTestData);

        $result = $this->controller->test();

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedTestData, $result->getData(true));
    }
}
