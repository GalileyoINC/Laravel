<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Podcast\CreatePodcastAction;
use App\Domain\Actions\Podcast\ExportPodcastsToCsvAction;
use App\Domain\Actions\Podcast\GetPodcastListAction;
use App\Domain\Actions\Podcast\UpdatePodcastAction;
use App\Domain\DTOs\Podcast\PodcastCreateDTO;
use App\Domain\DTOs\Podcast\PodcastUpdateDTO;
use App\Http\Controllers\Controller;
use App\Models\Content\Podcast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PodcastController extends Controller
{
    public function __construct(
        private readonly GetPodcastListAction $getPodcastListAction,
        private readonly ExportPodcastsToCsvAction $exportPodcastsToCsvAction,
        private readonly CreatePodcastAction $createPodcastAction,
        private readonly UpdatePodcastAction $updatePodcastAction,
    ) {}

    /**
     * Display Podcasts
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'type', 'created_at_from', 'created_at_to']);
        $podcasts = $this->getPodcastListAction->execute($filters, 20);

        return ViewFacade::make('podcast.index', [
            'podcasts' => $podcasts,
            'filters' => $filters,
        ]);
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        return ViewFacade::make('podcast.create');
    }

    /**
     * Store new Podcast
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'type' => 'required|string|in:audio,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $dto = new PodcastCreateDTO(
            title: (string) $request->input('title'),
            url: (string) $request->input('url'),
            type: (string) $request->input('type'),
            image: $request->file('image'),
        );

        $this->createPodcastAction->execute($dto);

        return redirect()->route('podcast.index')
            ->with('success', 'Podcast created successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(Podcast $podcast): View
    {
        return ViewFacade::make('podcast.edit', [
            'podcast' => $podcast,
        ]);
    }

    /**
     * Update Podcast
     */
    public function update(Request $request, Podcast $podcast): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'type' => 'required|string|in:audio,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $dto = new PodcastUpdateDTO(
            id: $podcast->id,
            title: (string) $request->input('title'),
            url: (string) $request->input('url'),
            type: (string) $request->input('type'),
            image: $request->file('image'),
        );

        $this->updatePodcastAction->execute($dto);

        return redirect()->route('podcast.index')
            ->with('success', 'Podcast updated successfully.');
    }

    /**
     * Delete Podcast
     */
    public function destroy(Podcast $podcast): RedirectResponse
    {
        // Delete image if exists
        if ($podcast->image && Storage::disk('public')->exists($podcast->image)) {
            Storage::disk('public')->delete($podcast->image);
        }

        $podcast->delete();

        return redirect()->route('podcast.index')
            ->with('success', 'Podcast deleted successfully.');
    }

    /**
     * Export Podcasts to CSV
     */
    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'type', 'created_at_from', 'created_at_to']);
        $rows = $this->exportPodcastsToCsvAction->execute($filters);
        $filename = 'podcasts_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $file = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
