<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User\EmergencyTipsRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class EmergencyTipsRequestController extends Controller
{
    /**
     * Display Emergency Tips Requests
     */
    public function index(Request $request): View
    {
        $query = EmergencyTipsRequest::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by first name
        if ($request->filled('first_name')) {
            $query->where('first_name', 'like', "%{$request->get('first_name')}%");
        }

        // Filter by email
        if ($request->filled('email')) {
            $query->where('email', 'like', "%{$request->get('email')}%");
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $emergencyTipsRequests = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('emergency-tips-request.index', [
            'emergencyTipsRequests' => $emergencyTipsRequests,
            'filters' => $request->only(['search', 'first_name', 'email', 'created_at_from', 'created_at_to']),
        ]);
    }

    /**
     * Show Emergency Tips Request Details
     */
    public function show(EmergencyTipsRequest $emergencyTipsRequest): View
    {
        return ViewFacade::make('emergency-tips-request.show', [
            'emergencyTipsRequest' => $emergencyTipsRequest,
        ]);
    }

    /**
     * Export Emergency Tips Requests to CSV
     */
    public function export(Request $request): Response
    {
            $query = EmergencyTipsRequest::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('first_name')) {
                $query->where('first_name', 'like', "%{$request->get('first_name')}%");
            }

            if ($request->filled('email')) {
                $query->where('email', 'like', "%{$request->get('email')}%");
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $emergencyTipsRequests = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'First Name', 'Email', 'Created At'];

            foreach ($emergencyTipsRequests as $emergencyTipsRequest) {
                $csvData[] = [
                    $emergencyTipsRequest->id,
                    $emergencyTipsRequest->first_name,
                    $emergencyTipsRequest->email,
                    $emergencyTipsRequest->created_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'emergency_tips_requests_'.now()->format('Y-m-d_H-i-s').'.csv';

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
