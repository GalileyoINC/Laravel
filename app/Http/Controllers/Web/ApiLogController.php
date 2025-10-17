<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\System\ApiLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class ApiLogController extends Controller
{
    /**
     * Display API Logs
     */
    public function index(Request $request): View
    {
        $query = ApiLog::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            });
        }

        // Filter by key
        if ($request->filled('key')) {
            $query->where('key', 'like', "%{$request->get('key')}%");
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $apiLogs = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('web.api-log.index', [
            'apiLogs' => $apiLogs,
            'filters' => $request->only(['search', 'key', 'created_at_from', 'created_at_to']),
        ]);
    }

    /**
     * Show API Log Details
     */
    public function show(ApiLog $apiLog): View
    {
        return ViewFacade::make('web.api-log.show', [
            'apiLog' => $apiLog,
        ]);
    }

    /**
     * Delete API Logs by Key
     */
    public function deleteByKey(ApiLog $apiLog): Response
    {
        try {
            $key = $apiLog->key;
            ApiLog::where('key', $key)->delete();

            return redirect()->route('web.api-log.index')
                ->with('success', "All API logs with key '{$key}' deleted successfully.");

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete API logs: '.$e->getMessage()]);
        }
    }

    /**
     * Export API Logs to CSV
     */
    public function export(Request $request): Response
    {
        try {
            $query = ApiLog::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('key', 'like', "%{$search}%")
                        ->orWhere('value', 'like', "%{$search}%");
                });
            }

            if ($request->filled('key')) {
                $query->where('key', 'like', "%{$request->get('key')}%");
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $apiLogs = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Key', 'Value', 'Created At'];

            foreach ($apiLogs as $apiLog) {
                $csvData[] = [
                    $apiLog->id,
                    $apiLog->key,
                    is_array($apiLog->value) ? json_encode($apiLog->value) : $apiLog->value,
                    $apiLog->created_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'api_logs_'.now()->format('Y-m-d_H-i-s').'.csv';

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
                ->withErrors(['error' => 'Failed to export API logs: '.$e->getMessage()]);
        }
    }
}
