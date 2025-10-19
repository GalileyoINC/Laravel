<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Content\GetPageListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Content\Web\PageContentRequest;
use App\Http\Requests\Content\Web\PageIndexRequest;
use App\Http\Requests\Content\Web\PageRequest;
use App\Models\Content\Page;
use App\Models\Content\PageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Str;

class PageController extends Controller
{
    public function __construct(
        private readonly GetPageListAction $getPageListAction,
    ) {}

    /**
     * Display a listing of pages
     */
    public function index(PageIndexRequest $request): View
    {
        $filters = $request->validated();
        $pages = $this->getPageListAction->execute($filters, 20);

        return ViewFacade::make('page.index', [
            'pages' => $pages,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new page
     */
    public function create(): View
    {
        return ViewFacade::make('page.create');
    }

    /**
     * Store a newly created page
     */
    public function store(PageRequest $request): Response
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'title' => $validated['title'] ?? null,
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'status' => $validated['status'] ?? Page::STATUS_OFF,
        ];

        $page = Page::create($data);

        return redirect()->route('page.edit', $page)
            ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified page
     */
    public function show(Page $page): View
    {
        $page->load('pageContents');

        return ViewFacade::make('page.show', [
            'page' => $page,
        ]);
    }

    /**
     * Show the form for editing the specified page
     */
    public function edit(Page $page): View
    {
        $page->load('pageContents');

        return ViewFacade::make('page.edit', [
            'page' => $page,
        ]);
    }

    /**
     * Update the specified page
     */
    public function update(PageRequest $request, Page $page): Response
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'title' => $validated['title'] ?? null,
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'status' => $validated['status'] ?? $page->status,
        ];

        $page->update($data);

        return redirect()->route('page.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Show content form for page
     */
    public function content(Page $page, ?PageContent $pageContent = null): View
    {
        return ViewFacade::make('page.content', [
            'page' => $page,
            'pageContent' => $pageContent,
        ]);
    }

    /**
     * Store page content
     */
    public function storeContent(PageContentRequest $request, Page $page): Response
    {
        $validated = $request->validated();

        $data = [
            'id_page' => $page->id,
            'content' => $validated['content'],
            'status' => $validated['status'] ?? PageContent::STATUS_DRAFT,
        ];

        PageContent::create($data);

        return redirect()->route('page.edit', $page)
            ->with('success', 'Page content created successfully.');
    }

    /**
     * Update page content
     */
    public function updateContent(PageContentRequest $request, Page $page, PageContent $pageContent): Response
    {
        $validated = $request->validated();

        $data = [
            'content' => $validated['content'],
            'status' => $validated['status'] ?? $pageContent->status,
        ];

        $pageContent->update($data);

        return redirect()->route('page.edit', $page)
            ->with('success', 'Page content updated successfully.');
    }

    /**
     * Upload image for page
     */
    public function upload(Request $request, Page $page): Response
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->store('pages', 'public');

        return response()->json([
            'location' => Storage::url($path),
        ]);
    }
}
