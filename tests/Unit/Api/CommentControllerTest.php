<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Domain\Actions\Comment\CreateCommentAction;
use App\Domain\Actions\Comment\GetCommentsAction;
use App\Http\Controllers\Api\CommentController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    private CommentController $controller;

    private GetCommentsAction $getCommentsAction;

    private CreateCommentAction $createCommentAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getCommentsAction = Mockery::mock(GetCommentsAction::class);
        $this->createCommentAction = Mockery::mock(CreateCommentAction::class);

        $this->controller = new CommentController(
            $this->getCommentsAction,
            $this->createCommentAction
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_calls_get_comments_action(): void
    {
        $request = new Request(['news_id' => 1]);
        $expectedResponse = new JsonResponse(['comments' => []]);

        $this->getCommentsAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->get($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_get_replies_returns_not_implemented_message(): void
    {
        $request = new Request(['comment_id' => 1]);

        $result = $this->controller->getReplies($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('Get replies endpoint not implemented yet', $data['message']);
    }

    public function test_create_calls_create_comment_action(): void
    {
        $request = new Request(['news_id' => 1, 'content' => 'Great article!']);
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $this->createCommentAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($expectedResponse);

        $result = $this->controller->create($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_update_returns_not_implemented_message(): void
    {
        $request = new Request(['comment_id' => 1, 'content' => 'Updated comment']);

        $result = $this->controller->update($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('Update comment endpoint not implemented yet', $data['message']);
    }

    public function test_delete_returns_not_implemented_message(): void
    {
        $request = new Request(['comment_id' => 1]);

        $result = $this->controller->delete($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());

        $data = $result->getData(true);
        $this->assertEquals('Delete comment endpoint not implemented yet', $data['message']);
    }
}
