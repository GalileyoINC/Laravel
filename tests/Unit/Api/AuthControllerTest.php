<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Domain\Actions\Authentication\LoginAction;
use App\Http\Controllers\Api\AuthController;
use App\Http\Requests\Authentication\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    private AuthController $controller;

    private LoginAction $loginAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loginAction = Mockery::mock(LoginAction::class);
        $this->controller = new AuthController($this->loginAction);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_login_calls_login_action_with_validated_data(): void
    {
        $request = Mockery::mock(LoginRequest::class);
        $validatedData = ['email' => 'test@example.com', 'password' => 'password'];
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $this->loginAction->shouldReceive('execute')->once()->with($validatedData)->andReturn($expectedResponse);

        $result = $this->controller->login($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_web_login_with_valid_credentials(): void
    {
        $this->markTestSkipped('Requires database setup for unit tests');
    }

    public function test_web_login_with_invalid_credentials(): void
    {
        $this->markTestSkipped('Requires database setup for unit tests');
    }

    public function test_web_login_with_non_existent_user(): void
    {
        $this->markTestSkipped('Requires database setup for unit tests');
    }

    public function test_test_returns_success_response(): void
    {
        $result = $this->controller->test();

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Authentication module is working!', $data['data']['message']);
        $this->assertEquals('Authentication', $data['data']['module']);
        $this->assertEquals('1.0', $data['data']['version']);
        $this->assertArrayHasKey('time', $data['data']);
    }

    public function test_signup_returns_not_implemented_response(): void
    {
        $request = new Request();

        $result = $this->controller->signup($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Signup endpoint - to be implemented', $data['data']['message']);
        $this->assertEquals('Authentication', $data['data']['module']);
        $this->assertEquals('1.0', $data['data']['version']);
    }

    public function test_news_by_subscription_returns_not_implemented_response(): void
    {
        $request = new Request();

        $result = $this->controller->newsBySubscription($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('News by subscription endpoint - to be implemented', $data['data']['message']);
        $this->assertEquals('Authentication', $data['data']['module']);
        $this->assertEquals('1.0', $data['data']['version']);
    }

    public function test_options_returns_empty_response(): void
    {
        $result = $this->controller->options();

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEmpty($data);
    }
}
