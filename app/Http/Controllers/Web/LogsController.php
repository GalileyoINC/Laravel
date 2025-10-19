<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Logs\ExportActiveRecordLogsToCsvAction;
use App\Domain\Actions\Logs\ExportApiLogsToCsvAction;
use App\Domain\Actions\Logs\GetActiveRecordLogListAction;
use App\Domain\Actions\Logs\GetApiLogListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Logs\Web\ActiveRecordLogIndexRequest;
use App\Http\Requests\Logs\Web\ApiLogIndexRequest;
use App\Models\System\ApiLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogsController extends Controller
{
    public function __construct(
        private readonly GetActiveRecordLogListAction $getActiveRecordLogListAction,
        private readonly GetApiLogListAction $getApiLogListAction,
        private readonly ExportActiveRecordLogsToCsvAction $exportActiveRecordLogsToCsvAction,
        private readonly ExportApiLogsToCsvAction $exportApiLogsToCsvAction,
    ) {}

    /**
     * Display Active Record Logs
     */
    public function activeRecordLogs(ActiveRecordLogIndexRequest $request): View
    {
        $filters = $request->validated();
        $logs = $this->getActiveRecordLogListAction->execute($filters, 20);

        return ViewFacade::make('logs.active-record-logs', [
            'logs' => $logs,
            'filters' => $filters,
        ]);
    }

    /**
     * Display API Logs
     */
    public function apiLogs(ApiLogIndexRequest $request): View
    {
        $filters = $request->validated();
        $logs = $this->getApiLogListAction->execute($filters, 20);

        return ViewFacade::make('logs.api-logs', [
            'logs' => $logs,
            'filters' => $filters,
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
    public function deleteByKey(Request $request, string $key): RedirectResponse
    {
        if (! auth()->user()?->isSuper()) {
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
    public function exportActiveRecordLogs(ActiveRecordLogIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportActiveRecordLogsToCsvAction->execute($filters);
        $filename = 'active_record_logs_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                throw new RuntimeException('Failed to open output stream');
            }
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
    public function exportApiLogs(ApiLogIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportApiLogsToCsvAction->execute($filters);
        $filename = 'api_logs_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                throw new RuntimeException('Failed to open output stream');
            }
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
