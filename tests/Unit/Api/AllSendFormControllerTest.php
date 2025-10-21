<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Domain\Actions\AllSendForm\GetAllSendOptionsAction;
use App\Domain\DTOs\AllSendForm\AllSendBroadcastRequestDTO;
use App\Domain\DTOs\AllSendForm\AllSendImageUploadRequestDTO;
use App\Domain\Services\AllSendForm\AllSendFormServiceInterface;
use App\Http\Controllers\Api\AllSendFormController;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class AllSendFormControllerTest extends TestCase
{
    private AllSendFormController $controller;

    private AllSendFormServiceInterface $allSendFormService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->allSendFormService = Mockery::mock(AllSendFormServiceInterface::class);
        $getAllSendOptionsAction = new GetAllSendOptionsAction($this->allSendFormService);

        $this->controller = new AllSendFormController(
            $getAllSendOptionsAction,
            $this->allSendFormService
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_options_returns_json_response(): void
    {
        $request = new Request();

        // Since we can't mock the readonly action, we'll test the method exists and returns JsonResponse
        $result = $this->controller->getOptions($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
    }

    public function test_send_with_authenticated_user(): void
    {
        $user = User::factory()->make();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $request = new Request(['text' => 'Test broadcast']);
        $expectedResult = ['status' => 'success'];

        $this->allSendFormService->shouldReceive('sendBroadcast')
            ->once()
            ->with(Mockery::type(AllSendBroadcastRequestDTO::class), $user)
            ->andReturn($expectedResult);

        $result = $this->controller->send($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedResult, $result->getData(true));
    }

    public function test_send_with_unauthenticated_user(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(null);

        $request = new Request(['message' => 'Test broadcast']);

        $result = $this->controller->send($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(401, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('User not authenticated', $data['error']);
        $this->assertEquals(401, $data['code']);
    }

    public function test_image_upload_with_authenticated_user(): void
    {
        $user = User::factory()->make();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $request = new Request(['uuid' => 'uuid-123']);
        $tmpFile = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($tmpFile, 'fake');
        $uploaded = new \Illuminate\Http\UploadedFile($tmpFile, 'test.jpg', null, null, true);
        $request->files->set('file', $uploaded);
        $expectedResult = ['status' => 'success'];

        $this->allSendFormService->shouldReceive('uploadImage')
            ->once()
            ->with(Mockery::type(AllSendImageUploadRequestDTO::class), $user)
            ->andReturn($expectedResult);

        $result = $this->controller->imageUpload($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedResult, $result->getData(true));
    }

    public function test_image_upload_with_unauthenticated_user(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(null);

        $request = new Request(['image' => 'base64data']);

        $result = $this->controller->imageUpload($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(401, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('User not authenticated', $data['error']);
        $this->assertEquals(401, $data['code']);
    }

    public function test_image_delete_with_authenticated_user(): void
    {
        $user = User::factory()->make();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $request = new Request(['id_image' => 123]);
        $expectedResult = ['status' => 'success'];

        $this->allSendFormService->shouldReceive('deleteImage')
            ->once()
            ->with(123, $user)
            ->andReturn($expectedResult);

        $result = $this->controller->imageDelete($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedResult, $result->getData(true));
    }

    public function test_image_delete_with_unauthenticated_user(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(null);

        $request = new Request(['id_image' => 123]);

        $result = $this->controller->imageDelete($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(401, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('User not authenticated', $data['error']);
        $this->assertEquals(401, $data['code']);
    }

    public function test_image_delete_with_missing_image_id(): void
    {
        $user = User::factory()->make();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $request = new Request();

        $result = $this->controller->imageDelete($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(400, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals(['Image ID is required'], $data['errors']);
        $this->assertEquals('Invalid request parameters', $data['message']);
    }
}
