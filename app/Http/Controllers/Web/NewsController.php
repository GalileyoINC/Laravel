<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\News\CreateNewsAction;
use App\Domain\Actions\News\GetLastNewsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\News\Web\NewsRequest;
use App\Models\Content\News;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(
        private readonly CreateNewsAction $createNewsAction,
        private readonly GetLastNewsAction $getLastNewsAction
    ) {}

    /**
     * Display a listing of news
     */
    public function index(Request $request): View
    {
        $query = News::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $news = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('news.index', [
            'news' => $news,
            'filters' => $request->only(['search', 'status']),
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

            $data = [
                'name' => $validated['name'],
                'title' => $validated['title'] ?? null,
                'meta_keywords' => $validated['meta_keywords'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'status' => $validated['status'] ?? 0,
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('news', 'public');
            }

            $data['slug'] = Str::slug($data['name']);

            $news = News::create($data);

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

            $data = [
                'name' => $validated['name'],
                'title' => $validated['title'] ?? null,
                'meta_keywords' => $validated['meta_keywords'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'status' => $validated['status'] ?? 0,
            ];

            if ($request->hasFile('image')) {
                if ($news->image) {
                    Storage::disk('public')->delete($news->image);
                }
                $data['image'] = $request->file('image')->store('news', 'public');
            }

            $data['slug'] = Str::slug($data['name']);

            $news->update($data);

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
