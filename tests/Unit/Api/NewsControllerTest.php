<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Domain\Actions\News\CreateNewsAction;
use App\Domain\Actions\News\GetLastNewsAction;
use App\Domain\Actions\News\GetNewsByFollowerListAction;
use App\Domain\Actions\News\GetNewsByInfluencersAction;
use App\Domain\Actions\News\MuteSubscriptionAction;
use App\Domain\Actions\News\RemoveReactionAction;
use App\Domain\Actions\News\ReportNewsAction;
use App\Domain\Actions\News\SetReactionAction;
use App\Domain\Actions\Posts\DeletePostAction;
use App\Domain\Actions\Posts\GetPostAction;
use App\Domain\Actions\Posts\UpdatePostAction;
use App\Http\Controllers\Api\NewsController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class NewsControllerTest extends TestCase
{
    private NewsController $controller;

    private GetLastNewsAction $getLastNewsAction;

    private GetNewsByInfluencersAction $getNewsByInfluencersAction;

    private SetReactionAction $setReactionAction;

    private RemoveReactionAction $removeReactionAction;

    private ReportNewsAction $reportNewsAction;

    private MuteSubscriptionAction $muteSubscriptionAction;

    private GetNewsByFollowerListAction $getNewsByFollowerListAction;

    private CreateNewsAction $createNewsAction;

    private GetPostAction $getPostAction;

    private UpdatePostAction $updatePostAction;

    private DeletePostAction $deletePostAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getLastNewsAction = Mockery::mock(GetLastNewsAction::class);
        $this->getNewsByInfluencersAction = Mockery::mock(GetNewsByInfluencersAction::class);
        $this->setReactionAction = Mockery::mock(SetReactionAction::class);
        $this->removeReactionAction = Mockery::mock(RemoveReactionAction::class);
        $this->reportNewsAction = Mockery::mock(ReportNewsAction::class);
        $this->muteSubscriptionAction = Mockery::mock(MuteSubscriptionAction::class);
        $this->getNewsByFollowerListAction = Mockery::mock(GetNewsByFollowerListAction::class);
        $this->createNewsAction = Mockery::mock(CreateNewsAction::class);
        $this->getPostAction = Mockery::mock(GetPostAction::class);
        $this->updatePostAction = Mockery::mock(UpdatePostAction::class);
        $this->deletePostAction = Mockery::mock(DeletePostAction::class);

        $this->controller = new NewsController(
            $this->getLastNewsAction,
            $this->getNewsByInfluencersAction,
            $this->setReactionAction,
            $this->removeReactionAction,
            $this->reportNewsAction,
            $this->muteSubscriptionAction,
            $this->getNewsByFollowerListAction,
            $this->createNewsAction,
            $this->getPostAction,
            $this->updatePostAction,
            $this->deletePostAction
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_last_calls_get_last_news_action(): void
    {
        $request = new Request(['page' => 1]);
        $expectedResponse = new JsonResponse(['news' => []]);

        $this->getLastNewsAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->last($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_get_latest_news_calls_get_last_news_action(): void
    {
        $request = new Request(['page' => 1]);
        $expectedResponse = new JsonResponse(['news' => []]);

        $this->getLastNewsAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->getLatestNews($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_by_influencers_calls_get_news_by_influencers_action(): void
    {
        $request = new Request(['influencer_ids' => [1, 2, 3]]);
        $expectedResponse = new JsonResponse(['news' => []]);

        $this->getNewsByInfluencersAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->byInfluencers($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_by_subscription_returns_not_implemented_message(): void
    {
        $request = new Request();

        $result = $this->controller->bySubscription($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('Get news by subscription endpoint not implemented yet', $data['message']);
    }

    public function test_set_reaction_calls_set_reaction_action(): void
    {
        $request = new Request(['news_id' => 1, 'reaction_type' => 'like']);
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->setReactionAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->setReaction($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_remove_reaction_calls_remove_reaction_action(): void
    {
        $request = new Request(['news_id' => 1, 'reaction_type' => 'like']);
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->removeReactionAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->removeReaction($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_by_follower_list_calls_get_news_by_follower_list_action(): void
    {
        $request = new Request(['follower_list_id' => 1]);
        $expectedResponse = new JsonResponse(['news' => []]);

        $this->getNewsByFollowerListAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->byFollowerList($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_report_calls_report_news_action(): void
    {
        $request = new Request(['news_id' => 1, 'reason' => 'spam']);
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->reportNewsAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->report($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_mute_calls_mute_subscription_action(): void
    {
        $request = new Request(['subscription_id' => 1]);
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->muteSubscriptionAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->mute($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_create_calls_create_news_action(): void
    {
        $request = new Request(['title' => 'Test News', 'content' => 'Test content']);
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->createNewsAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->create($request);

        $this->assertSame($expectedResponse, $result);
    }
}
