<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\System\ActiveRecordLog;
use App\Models\System\ApiLog;
use App\Models\User\Staff;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class LogsController extends Controller
{
    /**
     * Display Active Record Logs
     */
    public function activeRecordLogs(Request $request): View
    {
        $query = ActiveRecordLog::with(['user', 'staff']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('model', 'like', "%{$search}%")
                    ->orWhere('field', 'like', "%{$search}%")
                    ->orWhere('id_model', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('staff', function ($staffQuery) use ($search) {
                        $staffQuery->where('username', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by action type
        if ($request->filled('action_type')) {
            $query->where('action_type', $request->get('action_type'));
        }

        // Filter by model
        if ($request->filled('model')) {
            $query->where('model', $request->get('model'));
        }

        // Filter by user
        if ($request->filled('id_user')) {
            $query->where('id_user', $request->get('id_user'));
        }

        // Filter by staff
        if ($request->filled('id_staff')) {
            $query->where('id_staff', $request->get('id_staff'));
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('logs.active-record-logs', [
            'logs' => $logs,
            'filters' => $request->only(['search', 'action_type', 'model', 'id_user', 'id_staff', 'created_at_from', 'created_at_to']),
        ]);
    }

    /**
     * Display API Logs
     */
    public function apiLogs(Request $request): View
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

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('logs.api-logs', [
            'logs' => $logs,
            'filters' => $request->only(['search', 'key', 'created_at_from', 'created_at_to']),
        ]);
    }

    /**
     * Show API Log details
     */
    public function showApiLog(ApiLog $apiLog): View
    {
        return ViewFacade::make('logs.api-log-show', [
            'apiLog' => $apiLog,
        ]);
    }

    /**
     * Delete API Log by key
     */
    public function deleteByKey(Request $request, string $key): Response
    {
            if (! auth()->user()->isSuper()) {
                return redirect()->back()
                    ->withErrors(['error' => 'Unauthorized access.']);
            }

            ApiLog::where('key', $key)->delete();

            return redirect()->route('logs.api-logs')
                ->with('success', 'API logs deleted successfully.');
    }

    /**
     * Export Active Record Logs to CSV
     */
    public function exportActiveRecordLogs(Request $request): Response
    {
            $query = ActiveRecordLog::with(['user', 'staff']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('model', 'like', "%{$search}%")
                        ->orWhere('field', 'like', "%{$search}%")
                        ->orWhere('id_model', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('staff', function ($staffQuery) use ($search) {
                            $staffQuery->where('username', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('action_type')) {
                $query->where('action_type', $request->get('action_type'));
            }

            if ($request->filled('model')) {
                $query->where('model', $request->get('model'));
            }

            if ($request->filled('id_user')) {
                $query->where('id_user', $request->get('id_user'));
            }

            if ($request->filled('id_staff')) {
                $query->where('id_staff', $request->get('id_staff'));
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $logs = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Created At', 'User', 'Staff', 'Action Type', 'Model', 'ID Model', 'Field', 'Changes'];

            foreach ($logs as $log) {
                $csvData[] = [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->getFullName()." ({$log->user->id})" : '',
                    $log->staff ? "{$log->staff->username} ({$log->staff->id})" : '',
                    $log->action_type,
                    $log->model,
                    $log->id_model,
                    $log->field,
                    json_encode($log->changes),
                ];
            }

            $filename = 'active_record_logs_'.now()->format('Y-m-d_H-i-s').'.csv';

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

    /**
     * Export API Logs to CSV
     */
    public function exportApiLogs(Request $request): Response
    {
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

            $logs = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Key', 'Value', 'Created At'];

            foreach ($logs as $log) {
                $csvData[] = [
                    $log->id,
                    $log->key,
                    $log->value,
                    $log->created_at->format('Y-m-d H:i:s'),
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
    }
}
