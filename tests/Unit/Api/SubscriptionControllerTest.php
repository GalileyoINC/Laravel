<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Domain\Actions\Subscription\SetSubscriptionAction;
use App\Domain\DTOs\Subscription\FeedOptionsDTO;
use App\Domain\DTOs\Subscription\MarketstackSubscriptionDTO;
use App\Domain\Services\Subscription\SubscriptionServiceInterface;
use App\Http\Controllers\Api\SubscriptionController;
use App\Models\Communication\SmsPoolPhoto;
use App\Models\User\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class SubscriptionControllerTest extends TestCase
{
    private SubscriptionController $controller;

    private SetSubscriptionAction $setSubscriptionAction;

    private SubscriptionServiceInterface $subscriptionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setSubscriptionAction = Mockery::mock(SetSubscriptionAction::class);
        $this->subscriptionService = Mockery::mock(SubscriptionServiceInterface::class);

        $this->controller = new SubscriptionController(
            $this->setSubscriptionAction,
            $this->subscriptionService
        );

        if (! Schema::hasTable('sms_pool_photo')) {
            Schema::create('sms_pool_photo', function (Blueprint $table) {
                $table->id();
                $table->json('sizes')->nullable();
                $table->string('folder_name')->nullable();
                $table->timestamps();
            });
        }
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_set_calls_set_subscription_action(): void
    {
        $request = new Request(['subscription_id' => 1, 'status' => 'active']);
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->setSubscriptionAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->set($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_satellite_set_calls_set_subscription_action_with_satellite_type(): void
    {
        $request = new Request(['subscription_id' => 1, 'status' => 'active']);
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->setSubscriptionAction->shouldReceive('execute')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['sub_type'] === 'satellite' && $data['subscription_id'] === 1;
            }))
            ->andReturn($expectedResponse);

        $result = $this->controller->satelliteSet($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_category_calls_subscription_service(): void
    {
        $expectedCategories = ['category1', 'category2'];
        $expectedResponse = new JsonResponse($expectedCategories);

        $this->subscriptionService->shouldReceive('getFeedCategories')
            ->once()
            ->andReturn($expectedCategories);

        $result = $this->controller->category();

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedCategories, $result->getData());
    }

    public function test_index_calls_subscription_service_with_feed_options_dto(): void
    {
        $user = User::factory()->make();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $request = new Request(['page' => 1, 'per_page' => 10]);
        $expectedFeeds = ['feed1', 'feed2'];

        $this->subscriptionService->shouldReceive('getFeedList')
            ->once()
            ->with(Mockery::type(FeedOptionsDTO::class), $user)
            ->andReturn($expectedFeeds);

        $result = $this->controller->index($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedFeeds, $result->getData());
    }

    public function test_satellite_index_calls_subscription_service_with_feed_options_dto(): void
    {
        $user = User::factory()->make();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $request = new Request(['page' => 1, 'per_page' => 10]);
        $expectedFeeds = ['satellite_feed1', 'satellite_feed2'];

        $this->subscriptionService->shouldReceive('getSatelliteFeedList')
            ->once()
            ->with(Mockery::type(FeedOptionsDTO::class), $user)
            ->andReturn($expectedFeeds);

        $result = $this->controller->satelliteIndex($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedFeeds, $result->getData());
    }

    public function test_add_own_marketstack_indx_subscription_with_valid_data(): void
    {
        $user = User::factory()->make();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $request = new Request(['symbol' => 'AAPL', 'name' => 'Apple Inc.']);
        $expectedResult = ['status' => 'success'];

        $this->subscriptionService->shouldReceive('addMarketstackSubscription')
            ->once()
            ->with(Mockery::type(MarketstackSubscriptionDTO::class), $user)
            ->andReturn($expectedResult);

        $result = $this->controller->addOwnMarketstackIndxSubscription($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedResult, $result->getData(true));
    }

    public function test_add_own_marketstack_indx_subscription_with_unauthenticated_user(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(null);

        $request = new Request(['symbol' => 'AAPL']);

        $result = $this->controller->addOwnMarketstackIndxSubscription($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(401, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('User not authenticated', $data['error']);
    }

    public function test_add_own_marketstack_ticker_subscription_with_valid_data(): void
    {
        $user = User::factory()->make();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $request = new Request(['symbol' => 'AAPL', 'name' => 'Apple Inc.']);
        $expectedResult = ['status' => 'success'];

        $this->subscriptionService->shouldReceive('addMarketstackSubscription')
            ->once()
            ->with(Mockery::type(MarketstackSubscriptionDTO::class), $user)
            ->andReturn($expectedResult);

        $result = $this->controller->addOwnMarketstackTickerSubscription($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedResult, $result->getData(true));
    }

    public function test_add_own_marketstack_ticker_subscription_with_unauthenticated_user(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(null);

        $request = new Request(['symbol' => 'AAPL']);

        $result = $this->controller->addOwnMarketstackTickerSubscription($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(401, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('User not authenticated', $data['error']);
    }

    public function test_options_calls_subscription_service(): void
    {
        $expectedOptions = ['option1', 'option2'];

        $this->subscriptionService->shouldReceive('getFeedOptions')
            ->once()
            ->andReturn($expectedOptions);

        $result = $this->controller->options();

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedOptions, $result->getData());
    }

    public function test_delete_private_feed_with_valid_id(): void
    {
        $user = User::factory()->make();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $request = new Request(['id' => 1]);
        $expectedResult = ['status' => 'success'];

        $this->subscriptionService->shouldReceive('deletePrivateFeed')
            ->once()
            ->with(1, $user)
            ->andReturn($expectedResult);

        $result = $this->controller->deletePrivateFeed($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedResult, $result->getData(true));
    }

    public function test_delete_private_feed_with_missing_id(): void
    {
        $user = User::factory()->make();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $request = new Request();

        $result = $this->controller->deletePrivateFeed($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(400, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals(['ID is required'], $data['errors']);
    }

    public function test_delete_private_feed_with_unauthenticated_user(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(null);

        $request = new Request(['id' => 1]);

        $result = $this->controller->deletePrivateFeed($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(401, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('User not authenticated', $data['error']);
    }

    public function test_get_image_with_valid_id(): void
    {
        $smsPoolPhoto = SmsPoolPhoto::factory()->create([
            'sizes' => ['normal' => ['name' => 'test_image.jpg']],
            'folder_name' => 'test_folder',
        ]);
        $tmpFile = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($tmpFile, 'test');
        Storage::shouldReceive('disk')->with('public')->andReturnSelf();
        Storage::shouldReceive('exists')->with('test_folder/test_image.jpg')->andReturn(true);
        Storage::shouldReceive('path')->with('test_folder/test_image.jpg')->andReturn($tmpFile);

        $request = new Request(['id' => $smsPoolPhoto->id, 'type' => 'normal']);

        $result = $this->controller->getImage($request);

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\BinaryFileResponse::class, $result);
    }

    public function test_get_image_with_missing_id(): void
    {
        $request = new Request();

        $result = $this->controller->getImage($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(400, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('Image ID is required', $data['error']);
    }

    public function test_get_image_with_non_existent_photo(): void
    {
        $request = new Request(['id' => 999]);

        $result = $this->controller->getImage($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(404, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('Image not found', $data['error']);
    }
}
