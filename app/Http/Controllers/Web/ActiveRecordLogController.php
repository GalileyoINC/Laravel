<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\System\ActiveRecordLog;
use App\Models\System\Staff;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class ActiveRecordLogController extends Controller
{
    /**
     * Display Active Record Logs
     */
    public function index(Request $request): View
    {
        $query = ActiveRecordLog::with(['user', 'staff']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('model', 'like', "%{$search}%")
                    ->orWhere('field', 'like', "%{$search}%")
                    ->orWhere('id_model', 'like', "%{$search}%");
            });
        }

        // Filter by user
        if ($request->filled('userName')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->get('userName')}%")
                    ->orWhere('last_name', 'like', "%{$request->get('userName')}%")
                    ->orWhere('email', 'like', "%{$request->get('userName')}%");
            });
        }

        // Filter by staff
        if ($request->filled('staffName')) {
            $query->whereHas('staff', function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->get('staffName')}%");
            });
        }

        // Filter by action type
        if ($request->filled('action_type')) {
            $query->where('action_type', $request->get('action_type'));
        }

        // Filter by model
        if ($request->filled('model')) {
            $query->where('model', 'like', "%{$request->get('model')}%");
        }

        // Filter by field
        if ($request->filled('field')) {
            $query->where('field', 'like', "%{$request->get('field')}%");
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $activeRecordLogs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get dropdown data
        $actionTypes = ActiveRecordLog::getActionTypeList();
        $staffList = Staff::getForDropDown();

        return ViewFacade::make('active-record-log.index', [
            'activeRecordLogs' => $activeRecordLogs,
            'actionTypes' => $actionTypes,
            'staffList' => $staffList,
            'filters' => $request->only(['search', 'userName', 'staffName', 'action_type', 'model', 'field', 'created_at_from', 'created_at_to']),
        ]);
    }

    /**
     * Export Active Record Logs to CSV
     */
    public function export(Request $request): Response
    {
            $query = ActiveRecordLog::with(['user', 'staff']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('model', 'like', "%{$search}%")
                        ->orWhere('field', 'like', "%{$search}%")
                        ->orWhere('id_model', 'like', "%{$search}%");
                });
            }

            if ($request->filled('userName')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('first_name', 'like', "%{$request->get('userName')}%")
                        ->orWhere('last_name', 'like', "%{$request->get('userName')}%")
                        ->orWhere('email', 'like', "%{$request->get('userName')}%");
                });
            }

            if ($request->filled('staffName')) {
                $query->whereHas('staff', function ($q) use ($request) {
                    $q->where('username', 'like', "%{$request->get('staffName')}%");
                });
            }

            if ($request->filled('action_type')) {
                $query->where('action_type', $request->get('action_type'));
            }

            if ($request->filled('model')) {
                $query->where('model', 'like', "%{$request->get('model')}%");
            }

            if ($request->filled('field')) {
                $query->where('field', 'like', "%{$request->get('field')}%");
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $activeRecordLogs = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Created At', 'User', 'Staff', 'Action Type', 'Model', 'ID Model', 'Field', 'Changes'];

            foreach ($activeRecordLogs as $log) {
                $csvData[] = [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->getFullName()." ({$log->user->id})" : '',
                    $log->staff ? "{$log->staff->username} ({$log->staff->id})" : '',
                    ActiveRecordLog::getActionTypeList()[$log->action_type] ?? $log->action_type,
                    $log->model,
                    $log->id_model,
                    $log->field,
                    is_array($log->changes) ? json_encode($log->changes) : $log->changes,
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
}
