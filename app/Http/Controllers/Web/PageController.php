<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Content\CreatePageAction;
use App\Domain\Actions\Content\CreatePageContentAction;
use App\Domain\Actions\Content\GetPageListAction;
use App\Domain\Actions\Content\UpdatePageAction;
use App\Domain\Actions\Content\UpdatePageContentAction;
use App\Domain\Actions\Content\UploadPageImageAction;
use App\Domain\DTOs\Content\PageContentCreateDTO;
use App\Domain\DTOs\Content\PageContentUpdateDTO;
use App\Domain\DTOs\Content\PageCreateDTO;
use App\Domain\DTOs\Content\PageImageUploadDTO;
use App\Domain\DTOs\Content\PageUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Content\Web\PageContentRequest;
use App\Http\Requests\Content\Web\PageIndexRequest;
use App\Http\Requests\Content\Web\PageRequest;
use App\Models\Content\Page;
use App\Models\Content\PageContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function __construct(
        private readonly GetPageListAction $getPageListAction,
        private readonly CreatePageAction $createPageAction,
        private readonly UpdatePageAction $updatePageAction,
        private readonly CreatePageContentAction $createPageContentAction,
        private readonly UpdatePageContentAction $updatePageContentAction,
        private readonly UploadPageImageAction $uploadPageImageAction,
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
    public function store(PageRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $dto = new PageCreateDTO(
            name: $validated['name'],
            title: $validated['title'] ?? null,
            slug: $validated['slug'] ?? Str::slug($validated['name']),
            metaKeywords: $validated['meta_keywords'] ?? null,
            metaDescription: $validated['meta_description'] ?? null,
            status: (int) ($validated['status'] ?? Page::STATUS_OFF),
        );

        $page = $this->createPageAction->execute($dto);

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
    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $validated = $request->validated();

        $dto = new PageUpdateDTO(
            id: $page->id,
            name: $validated['name'],
            title: $validated['title'] ?? null,
            slug: $validated['slug'] ?? Str::slug($validated['name']),
            metaKeywords: $validated['meta_keywords'] ?? null,
            metaDescription: $validated['meta_description'] ?? null,
            status: (int) ($validated['status'] ?? $page->status),
        );

        $this->updatePageAction->execute($dto);

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
    public function storeContent(PageContentRequest $request, Page $page): RedirectResponse
    {
        $validated = $request->validated();

        $dto = new PageContentCreateDTO(
            pageId: $page->id,
            content: $validated['content'],
            status: (int) ($validated['status'] ?? PageContent::STATUS_DRAFT),
        );

        $this->createPageContentAction->execute($dto);

        return redirect()->route('page.edit', $page)
            ->with('success', 'Page content created successfully.');
    }

    /**
     * Update page content
     */
    public function updateContent(PageContentRequest $request, Page $page, PageContent $pageContent): RedirectResponse
    {
        $validated = $request->validated();

        $dto = new PageContentUpdateDTO(
            id: $pageContent->id,
            content: $validated['content'],
            status: (int) ($validated['status'] ?? $pageContent->status),
        );

        $this->updatePageContentAction->execute($dto);

        return redirect()->route('page.edit', $page)
            ->with('success', 'Page content updated successfully.');
    }

    /**
     * Upload image for page
     */
    public function upload(Request $request, Page $page): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $file = $request->file('file');
        $dto = new PageImageUploadDTO(pageId: $page->id, file: $file);
        $result = $this->uploadPageImageAction->execute($dto);

        return response()->json([
            'location' => $result['location'],
        ]);
    }
}
