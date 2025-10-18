<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\Web\InfluencerAssistantRequest;
use App\Models\Subscription\InfluencerAssistant;
use App\Models\User\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class InfluencerAssistantController extends Controller
{
    /**
     * Display Influencer Assistants
     */
    public function index(Request $request): View
    {
        $query = InfluencerAssistant::with(['influencer', 'assistant']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('influencer', function ($userQuery) use ($search) {
                    $userQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhereHas('assistant', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by influencer name
        if ($request->filled('userInfluencerName')) {
            $query->whereHas('influencer', function ($userQuery) use ($request) {
                $userQuery->where('first_name', 'like', "%{$request->get('userInfluencerName')}%")
                    ->orWhere('last_name', 'like', "%{$request->get('userInfluencerName')}%");
            });
        }

        // Filter by assistant name
        if ($request->filled('userAssistantName')) {
            $query->whereHas('assistant', function ($userQuery) use ($request) {
                $userQuery->where('first_name', 'like', "%{$request->get('userAssistantName')}%")
                    ->orWhere('last_name', 'like', "%{$request->get('userAssistantName')}%");
            });
        }

        $influencerAssistants = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('influencer-assistant.index', [
            'influencerAssistants' => $influencerAssistants,
            'filters' => $request->only(['search', 'userInfluencerName', 'userAssistantName']),
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
    public function store(InfluencerAssistantRequest $request): Response
    {
            InfluencerAssistant::create($request->validated());

            return redirect()->route('influencer-assistant.index')
                ->with('success', 'Influencer assistant created successfully.');
    }

    /**
     * Delete Influencer Assistant
     */
    public function destroy(Request $request, int $idInfluencer, int $idAssistant): Response
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
    public function export(Request $request): Response
    {
            $query = InfluencerAssistant::with(['influencer', 'assistant']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->whereHas('influencer', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                        ->orWhereHas('assistant', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('userInfluencerName')) {
                $query->whereHas('influencer', function ($userQuery) use ($request) {
                    $userQuery->where('first_name', 'like', "%{$request->get('userInfluencerName')}%")
                        ->orWhere('last_name', 'like', "%{$request->get('userInfluencerName')}%");
                });
            }

            if ($request->filled('userAssistantName')) {
                $query->whereHas('assistant', function ($userQuery) use ($request) {
                    $userQuery->where('first_name', 'like', "%{$request->get('userAssistantName')}%")
                        ->orWhere('last_name', 'like', "%{$request->get('userAssistantName')}%");
                });
            }

            $influencerAssistants = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['Influencer', 'Assistant', 'Created At'];

            foreach ($influencerAssistants as $influencerAssistant) {
                $csvData[] = [
                    $influencerAssistant->influencer ? $influencerAssistant->influencer->first_name.' '.$influencerAssistant->influencer->last_name : '',
                    $influencerAssistant->assistant ? $influencerAssistant->assistant->first_name.' '.$influencerAssistant->assistant->last_name : '',
                    $influencerAssistant->created_at->format('Y-m-d H:i:s'),
                ];
            }

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
