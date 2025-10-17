<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Content\Podcast;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class PodcastController extends Controller
{
    /**
     * Display Podcasts
     */
    public function index(Request $request): View
    {
        $query = Podcast::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $podcasts = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('web.podcast.index', [
            'podcasts' => $podcasts,
            'filters' => $request->only(['search', 'type', 'created_at_from', 'created_at_to']),
        ]);
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        return ViewFacade::make('web.podcast.create');
    }

    /**
     * Store new Podcast
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'type' => 'required|string|in:audio,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $podcast = new Podcast();
            $podcast->title = $request->get('title');
            $podcast->url = $request->get('url');
            $podcast->type = $request->get('type');

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('podcasts', 'public');
                $podcast->image = $imagePath;
            }

            $podcast->save();

            return redirect()->route('web.podcast.index')
                ->with('success', 'Podcast created successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create podcast: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit(Podcast $podcast): View
    {
        return ViewFacade::make('web.podcast.edit', [
            'podcast' => $podcast,
        ]);
    }

    /**
     * Update Podcast
     */
    public function update(Request $request, Podcast $podcast): Response
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'type' => 'required|string|in:audio,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $podcast->title = $request->get('title');
            $podcast->url = $request->get('url');
            $podcast->type = $request->get('type');

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($podcast->image && Storage::disk('public')->exists($podcast->image)) {
                    Storage::disk('public')->delete($podcast->image);
                }

                $imagePath = $request->file('image')->store('podcasts', 'public');
                $podcast->image = $imagePath;
            }

            $podcast->save();

            return redirect()->route('web.podcast.index')
                ->with('success', 'Podcast updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update podcast: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Delete Podcast
     */
    public function destroy(Podcast $podcast): Response
    {
        try {
            // Delete image if exists
            if ($podcast->image && Storage::disk('public')->exists($podcast->image)) {
                Storage::disk('public')->delete($podcast->image);
            }

            $podcast->delete();

            return redirect()->route('web.podcast.index')
                ->with('success', 'Podcast deleted successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete podcast: '.$e->getMessage()]);
        }
    }

    /**
     * Export Podcasts to CSV
     */
    public function export(Request $request): Response
    {
        try {
            $query = Podcast::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%");
                });
            }

            if ($request->filled('type')) {
                $query->where('type', $request->get('type'));
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $podcasts = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Title', 'URL', 'Type', 'Image', 'Created At'];

            foreach ($podcasts as $podcast) {
                $csvData[] = [
                    $podcast->id,
                    $podcast->title,
                    $podcast->url,
                    ucfirst((string) $podcast->type),
                    $podcast->image ?: '-',
                    $podcast->created_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'podcasts_'.now()->format('Y-m-d_H-i-s').'.csv';

            return response()->streamDownload(function () use ($csvData) {
                $file = fopen('php://output', 'w');
                foreach ($csvData as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            }, $filename, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to export podcasts: '.$e->getMessage()]);
        }
    }
}
