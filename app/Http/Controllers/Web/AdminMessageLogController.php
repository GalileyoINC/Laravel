<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\System\AdminMessageLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class AdminMessageLogController extends Controller
{
    /**
     * Display Admin Message Logs
     */
    public function index(Request $request): View
    {
        $query = AdminMessageLog::query();

        // Filter by object type
        if ($request->filled('objType')) {
            $query->where('obj_type', $request->get('objType'));
        }

        // Filter by object ID
        if ($request->filled('objId')) {
            $query->where('obj_id', $request->get('objId'));
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('body', 'like', "%{$search}%");
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $adminMessageLogs = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('admin-message-log.index', [
            'adminMessageLogs' => $adminMessageLogs,
            'filters' => $request->only(['objType', 'objId', 'search', 'created_at_from', 'created_at_to']),
        ]);
    }

    /**
     * Export Admin Message Logs to CSV
     */
    public function export(Request $request): Response
    {
            $query = AdminMessageLog::query();

            // Apply same filters as index
            if ($request->filled('objType')) {
                $query->where('obj_type', $request->get('objType'));
            }

            if ($request->filled('objId')) {
                $query->where('obj_id', $request->get('objId'));
            }

            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where('body', 'like', "%{$search}%");
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $adminMessageLogs = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Object Type', 'Object ID', 'Message', 'Created At'];

            foreach ($adminMessageLogs as $log) {
                $csvData[] = [
                    $log->id,
                    $log->obj_type,
                    $log->obj_id,
                    $log->body,
                    $log->created_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'admin_message_logs_'.now()->format('Y-m-d_H-i-s').'.csv';

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
