<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Domain\Actions\Influencer\CreateInfluencerFeedAction;
use App\Domain\Actions\Influencer\GetInfluencerFeedListAction;
use App\Http\Controllers\Api\InfluencerController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class InfluencerControllerTest extends TestCase
{
    private InfluencerController $controller;

    private GetInfluencerFeedListAction $getInfluencerFeedListAction;

    private CreateInfluencerFeedAction $createInfluencerFeedAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getInfluencerFeedListAction = Mockery::mock(GetInfluencerFeedListAction::class);
        $this->createInfluencerFeedAction = Mockery::mock(CreateInfluencerFeedAction::class);

        $this->controller = new InfluencerController(
            $this->getInfluencerFeedListAction,
            $this->createInfluencerFeedAction
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_calls_get_influencer_feed_list_action(): void
    {
        $request = new Request(['page' => 1]);
        $expectedResponse = new JsonResponse(['influencers' => []]);

        $this->getInfluencerFeedListAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->index($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_create_calls_create_influencer_feed_action(): void
    {
        $request = new Request(['name' => 'Test Influencer', 'description' => 'Test description']);
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->createInfluencerFeedAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->create($request);

        $this->assertSame($expectedResponse, $result);
    }
}
