<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\Web\PageContentRequest;
use App\Http\Requests\Content\Web\PageRequest;
use App\Models\Content\Page;
use App\Models\Content\PageContent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Str;

class PageController extends Controller
{
    /**
     * Display a listing of pages
     */
    public function index(Request $request): View
    {
        $query = Page::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by date range
        if ($request->filled('createTimeRange')) {
            $dateRange = explode(' - ', (string) $request->get('createTimeRange'));
            if (count($dateRange) === 2) {
                $query->whereBetween('created_at', [
                    \Carbon\Carbon::parse($dateRange[0])->startOfDay(),
                    \Carbon\Carbon::parse($dateRange[1])->endOfDay(),
                ]);
            }
        }

        $pages = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('web.page.index', [
            'pages' => $pages,
            'filters' => $request->only(['search', 'status', 'createTimeRange']),
        ]);
    }

    /**
     * Show the form for creating a new page
     */
    public function create(): View
    {
        return ViewFacade::make('web.page.create');
    }

    /**
     * Store a newly created page
     */
    public function store(PageRequest $request): Response
    {
        try {
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

            return redirect()->route('web.page.edit', $page)
                ->with('success', 'Page created successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create page: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified page
     */
    public function show(Page $page): View
    {
        $page->load('pageContents');

        return ViewFacade::make('web.page.show', [
            'page' => $page,
        ]);
    }

    /**
     * Show the form for editing the specified page
     */
    public function edit(Page $page): View
    {
        $page->load('pageContents');

        return ViewFacade::make('web.page.edit', [
            'page' => $page,
        ]);
    }

    /**
     * Update the specified page
     */
    public function update(PageRequest $request, Page $page): Response
    {
        try {
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

            return redirect()->route('web.page.index')
                ->with('success', 'Page updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update page: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show content form for page
     */
    public function content(Page $page, ?PageContent $pageContent = null): View
    {
        return ViewFacade::make('web.page.content', [
            'page' => $page,
            'pageContent' => $pageContent,
        ]);
    }

    /**
     * Store page content
     */
    public function storeContent(PageContentRequest $request, Page $page): Response
    {
        try {
            $validated = $request->validated();

            $data = [
                'id_page' => $page->id,
                'content' => $validated['content'],
                'status' => $validated['status'] ?? PageContent::STATUS_DRAFT,
            ];

            PageContent::create($data);

            return redirect()->route('web.page.edit', $page)
                ->with('success', 'Page content created successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create page content: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Update page content
     */
    public function updateContent(PageContentRequest $request, Page $page, PageContent $pageContent): Response
    {
        try {
            $validated = $request->validated();

            $data = [
                'content' => $validated['content'],
                'status' => $validated['status'] ?? $pageContent->status,
            ];

            $pageContent->update($data);

            return redirect()->route('web.page.edit', $page)
                ->with('success', 'Page content updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update page content: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Upload image for page
     */
    public function upload(Request $request, Page $page): Response
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $file = $request->file('file');
            $path = $file->store('pages', 'public');

            return response()->json([
                'location' => Storage::url($path),
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to upload image: '.$e->getMessage()], 500);
        }
    }
}
