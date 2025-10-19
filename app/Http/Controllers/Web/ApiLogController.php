<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\ApiLog\ExportApiLogsToCsvAction;
use App\Domain\Actions\ApiLog\GetApiLogListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLog\Web\ApiLogRequest;
use App\Models\System\ApiLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiLogController extends Controller
{
    public function __construct(
        private readonly GetApiLogListAction $getApiLogListAction,
        private readonly ExportApiLogsToCsvAction $exportApiLogsToCsvAction,
    ) {}

    /**
     * Display API Logs
     */
    public function index(ApiLogRequest $request): View
    {
        $filters = $request->validated();
        $apiLogs = $this->getApiLogListAction->execute($filters, 20);

        return ViewFacade::make('api-log.index', [
            'apiLogs' => $apiLogs,
            'filters' => $filters,
        ]);
    }

    /**
     * Show API Log Details
     */
    public function show(ApiLog $apiLog): View
    {
        return ViewFacade::make('api-log.show', [
            'apiLog' => $apiLog,
        ]);
    }

    /**
     * Delete API Logs by Key
     */
    public function deleteByKey(ApiLog $apiLog): RedirectResponse
    {
        $key = $apiLog->key;
        ApiLog::where('key', $key)->delete();

        return redirect()->route('api-log.index')
            ->with('success', "All API logs with key '{$key}' deleted successfully.");
    }

    /**
     * Export API Logs to CSV
     */
    public function export(ApiLogRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $rows = $this->exportApiLogsToCsvAction->execute($filters);

        $filename = 'api_logs_'.now()->format('Y-m-d_H-i-s').'.csv';

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
