<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Domain\Actions\Customer\ChangePasswordAction;
use App\Domain\Actions\Customer\GetProfileAction;
use App\Domain\Actions\Customer\RemoveAvatarAction;
use App\Domain\Actions\Customer\RemoveHeaderAction;
use App\Domain\Actions\Customer\UpdatePrivacyAction;
use App\Domain\Actions\Customer\UpdateProfileAction;
use App\Domain\Services\Customer\CustomerServiceInterface;
use App\Http\Controllers\Api\CustomerController;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    private CustomerController $controller;

    private GetProfileAction $getProfileAction;

    private UpdateProfileAction $updateProfileAction;

    private ChangePasswordAction $changePasswordAction;

    private UpdatePrivacyAction $updatePrivacyAction;

    private RemoveAvatarAction $removeAvatarAction;

    private RemoveHeaderAction $removeHeaderAction;

    private CustomerServiceInterface $customerService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getProfileAction = Mockery::mock(GetProfileAction::class);
        $this->updateProfileAction = Mockery::mock(UpdateProfileAction::class);
        $this->changePasswordAction = Mockery::mock(ChangePasswordAction::class);
        $this->updatePrivacyAction = Mockery::mock(UpdatePrivacyAction::class);
        $this->removeAvatarAction = Mockery::mock(RemoveAvatarAction::class);
        $this->removeHeaderAction = Mockery::mock(RemoveHeaderAction::class);
        $this->customerService = Mockery::mock(CustomerServiceInterface::class);

        $this->controller = new CustomerController(
            $this->getProfileAction,
            $this->updateProfileAction,
            $this->changePasswordAction,
            $this->updatePrivacyAction,
            $this->removeAvatarAction,
            $this->removeHeaderAction,
            $this->customerService
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_profile_calls_get_profile_action(): void
    {
        $request = new Request();
        $expectedResponse = new JsonResponse(['profile' => []]);

        $this->getProfileAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->getProfile($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_update_profile_calls_update_profile_action(): void
    {
        $request = new Request(['first_name' => 'John', 'last_name' => 'Doe']);
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->updateProfileAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->updateProfile($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_change_password_calls_change_password_action(): void
    {
        $request = new Request(['current_password' => 'old', 'new_password' => 'new']);
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->changePasswordAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->changePassword($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_update_privacy_calls_update_privacy_action(): void
    {
        $request = new Request(['privacy_settings' => ['public' => true]]);
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->updatePrivacyAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->updatePrivacy($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_remove_avatar_calls_remove_avatar_action(): void
    {
        $request = new Request();
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->removeAvatarAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->removeAvatar($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_remove_header_calls_remove_header_action(): void
    {
        $request = new Request();
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->removeHeaderAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->removeHeader($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_logout_with_authenticated_user(): void
    {
        $user = User::factory()->make();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $expectedResult = ['status' => 'success'];

        $this->customerService->shouldReceive('logout')
            ->once()
            ->with($user)
            ->andReturn($expectedResult);

        $result = $this->controller->logout();

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedResult, $result->getData(true));
    }

    public function test_logout_with_unauthenticated_user(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(null);

        $result = $this->controller->logout();

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(401, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('User not authenticated', $data['error']);
        $this->assertEquals(401, $data['code']);
    }

    public function test_delete_with_authenticated_user(): void
    {
        $user = User::factory()->make();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $expectedResult = ['status' => 'success'];

        $this->customerService->shouldReceive('deleteAccount')
            ->once()
            ->with($user)
            ->andReturn($expectedResult);

        $result = $this->controller->delete();

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedResult, $result->getData(true));
    }

    public function test_delete_with_unauthenticated_user(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(null);

        $result = $this->controller->delete();

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(401, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('User not authenticated', $data['error']);
        $this->assertEquals(401, $data['code']);
    }
}
