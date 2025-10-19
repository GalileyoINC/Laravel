<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\InfluencerAssistant\ExportInfluencerAssistantsToCsvAction;
use App\Domain\Actions\InfluencerAssistant\GetInfluencerAssistantListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Content\Web\InfluencerAssistantRequest;
use App\Http\Requests\InfluencerAssistant\Web\InfluencerAssistantIndexRequest;
use App\Models\Subscription\InfluencerAssistant;
use App\Models\User\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InfluencerAssistantController extends Controller
{
    public function __construct(
        private readonly GetInfluencerAssistantListAction $getInfluencerAssistantListAction,
        private readonly ExportInfluencerAssistantsToCsvAction $exportInfluencerAssistantsToCsvAction,
    ) {}

    /**
     * Display Influencer Assistants
     */
    public function index(InfluencerAssistantIndexRequest $request): View
    {
        $filters = $request->validated();
        $influencerAssistants = $this->getInfluencerAssistantListAction->execute($filters, 20);

        return ViewFacade::make('influencer-assistant.index', [
            'influencerAssistants' => $influencerAssistants,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Influencer Assistant Create Form
     */
    public function create(): View
    {
        $users = User::orderBy('first_name')->get();

        $assistants = $users->pluck('full_name', 'id')->toArray();
        $influencers = $users->where('is_influencer', true)->pluck('full_name', 'id')->toArray();

        return ViewFacade::make('influencer-assistant.create', [
            'assistants' => $assistants,
            'influencers' => $influencers,
        ]);
    }

    /**
     * Store Influencer Assistant
     */
    public function store(InfluencerAssistantRequest $request): RedirectResponse
    {
        InfluencerAssistant::create($request->validated());

        return redirect()->route('influencer-assistant.index')
            ->with('success', 'Influencer assistant created successfully.');
    }

    /**
     * Delete Influencer Assistant
     */
    public function destroy(Request $request, int $idInfluencer, int $idAssistant): RedirectResponse
    {
        if (! $request->isMethod('POST')) {
            return redirect()->back()->withErrors(['error' => 'Method not allowed.']);
        }
        $influencerAssistant = InfluencerAssistant::where('id_influencer', $idInfluencer)
            ->where('id_assistant', $idAssistant)
            ->firstOrFail();

        $influencerAssistant->delete();

        return redirect()->route('influencer-assistant.index')
            ->with('success', 'Record deleted successfully.');
    }

    /**
     * Export Influencer Assistants to CSV
     */
    public function export(InfluencerAssistantIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportInfluencerAssistantsToCsvAction->execute($filters);
        $filename = 'influencer_assistants_'.now()->format('Y-m-d_H-i-s').'.csv';

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
    }
}
