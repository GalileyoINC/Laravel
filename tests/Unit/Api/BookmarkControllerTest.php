<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Domain\Actions\Bookmark\CreateBookmarkAction;
use App\Domain\Actions\Bookmark\DeleteBookmarkAction;
use App\Domain\Actions\Bookmark\GetBookmarksAction;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Requests\Bookmark\BookmarkListRequest;
use App\Http\Requests\Bookmark\BookmarkRequest;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class BookmarkControllerTest extends TestCase
{
    private BookmarkController $controller;

    private GetBookmarksAction $getBookmarksAction;

    private CreateBookmarkAction $createBookmarkAction;

    private DeleteBookmarkAction $deleteBookmarkAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getBookmarksAction = Mockery::mock(GetBookmarksAction::class);
        $this->createBookmarkAction = Mockery::mock(CreateBookmarkAction::class);
        $this->deleteBookmarkAction = Mockery::mock(DeleteBookmarkAction::class);

        $this->controller = new BookmarkController(
            $this->getBookmarksAction,
            $this->createBookmarkAction,
            $this->deleteBookmarkAction
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_list_calls_get_bookmarks_action(): void
    {
        $request = Mockery::mock(BookmarkListRequest::class);
        $validatedData = ['page' => 1];
        $expectedResponse = new JsonResponse(['bookmarks' => []]);

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $this->getBookmarksAction->shouldReceive('execute')
            ->once()
            ->with($validatedData)
            ->andReturn($expectedResponse);

        $result = $this->controller->list($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_index_calls_list_method(): void
    {
        $request = Mockery::mock(BookmarkListRequest::class);
        $validatedData = ['page' => 1];
        $expectedResponse = new JsonResponse(['bookmarks' => []]);

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $this->getBookmarksAction->shouldReceive('execute')
            ->once()
            ->with($validatedData)
            ->andReturn($expectedResponse);

        $result = $this->controller->index($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_create_calls_create_bookmark_action(): void
    {
        $request = Mockery::mock(BookmarkRequest::class);
        $validatedData = ['news_id' => 1];
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $this->createBookmarkAction->shouldReceive('execute')
            ->once()
            ->with($validatedData)
            ->andReturn($expectedResponse);

        $result = $this->controller->create($request);

        $this->assertSame($expectedResponse, $result);
    }

    public function test_delete_calls_delete_bookmark_action(): void
    {
        $request = Mockery::mock(BookmarkRequest::class);
        $validatedData = ['bookmark_id' => 1];
        $expectedResponse = new JsonResponse(['status' => 'success']);

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $this->deleteBookmarkAction->shouldReceive('execute')
            ->once()
            ->with($validatedData)
            ->andReturn($expectedResponse);

        $result = $this->controller->delete($request);

        $this->assertSame($expectedResponse, $result);
    }
}
