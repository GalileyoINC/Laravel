<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\News\GetNewsListAction;
use App\Domain\Actions\News\StoreNewsAction;
use App\Domain\Actions\News\UpdateNewsAction;
use App\Domain\DTOs\News\NewsCreateDTO;
use App\Domain\DTOs\News\NewsUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\News\Web\NewsIndexRequest;
use App\Http\Requests\News\Web\NewsRequest;
use App\Models\Content\News;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View as ViewFacade;

class NewsController extends Controller
{
    public function __construct(
        private readonly GetNewsListAction $getNewsListAction,
        private readonly StoreNewsAction $storeNewsAction,
        private readonly UpdateNewsAction $updateNewsAction,
    ) {}

    /**
     * Display a listing of news
     */
    public function index(NewsIndexRequest $request): View
    {
        $filters = $request->validated();
        $news = $this->getNewsListAction->execute($filters, 20);

        return ViewFacade::make('news.index', [
            'news' => $news,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new news
     */
    public function create(): View
    {
        return ViewFacade::make('news.create');
    }

    /**
     * Store a newly created news
     */
    public function store(NewsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $dto = new NewsCreateDTO(
            name: $validated['name'],
            title: $validated['title'] ?? null,
            metaKeywords: $validated['meta_keywords'] ?? null,
            metaDescription: $validated['meta_description'] ?? null,
            status: (int) ($validated['status'] ?? 0),
            image: $request->file('image'),
        );

        $this->storeNewsAction->execute($dto);

        return Redirect::to(route('news.index'))
            ->with('success', 'News created successfully.');
    }

    /**
     * Display the specified news
     */
    public function show(News $news): View
    {
        return ViewFacade::make('news.show', [
            'news' => $news,
        ]);
    }

    /**
     * Show the form for editing the specified news
     */
    public function edit(News $news): View
    {
        return ViewFacade::make('news.edit', [
            'news' => $news,
        ]);
    }

    /**
     * Update the specified news
     */
    public function update(NewsRequest $request, News $news): RedirectResponse
    {
        $validated = $request->validated();

        $dto = new NewsUpdateDTO(
            id: $news->id,
            name: $validated['name'],
            title: $validated['title'] ?? null,
            metaKeywords: $validated['meta_keywords'] ?? null,
            metaDescription: $validated['meta_description'] ?? null,
            status: (int) ($validated['status'] ?? 0),
            image: $request->file('image'),
        );

        $this->updateNewsAction->execute($dto);

        return Redirect::to(route('news.index'))
            ->with('success', 'News updated successfully.');
    }

    /**
     * Remove the specified news
     */
    public function destroy(News $news): RedirectResponse
    {
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }
        $news->delete();

        return Redirect::to(route('news.index'))
            ->with('success', 'News deleted successfully.');
    }

    /**
     * Toggle news status
     */
    public function toggleStatus(News $news): RedirectResponse
    {
        $news->update([
            'status' => $news->status === 1 ? 0 : 1,
        ]);

        $status = $news->status === 1 ? 'activated' : 'deactivated';

        return Redirect::back()
            ->with('success', "News {$status} successfully.");
    }

    /**
     * Show news content management
     */
    public function content(News $news): View
    {
        $news->load('news_contents');

        return ViewFacade::make('news.content', [
            'news' => $news,
        ]);
    }
}
