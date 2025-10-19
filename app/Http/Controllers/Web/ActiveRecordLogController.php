<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\ActiveRecordLog\ExportActiveRecordLogsToCsvAction;
use App\Domain\Actions\ActiveRecordLog\GetActiveRecordLogListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActiveRecordLog\Web\ActiveRecordLogRequest;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActiveRecordLogController extends Controller
{
    public function __construct(
        private readonly GetActiveRecordLogListAction $getActiveRecordLogListAction,
        private readonly ExportActiveRecordLogsToCsvAction $exportActiveRecordLogsToCsvAction,
    ) {}

    /**
     * Display Active Record Logs
     */
    public function index(ActiveRecordLogRequest $request): View
    {
        $filters = $request->validated();
        $result = $this->getActiveRecordLogListAction->execute($filters, 20);

        return ViewFacade::make('active-record-log.index', [
            'activeRecordLogs' => $result['logs'],
            'actionTypes' => $result['actionTypes'],
            'staffList' => $result['staffList'],
            'filters' => $filters,
        ]);
    }

    /**
     * Export Active Record Logs to CSV
     */
    public function export(ActiveRecordLogRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $rows = $this->exportActiveRecordLogsToCsvAction->execute($filters);

        $filename = 'active_record_logs_'.now()->format('Y-m-d_H-i-s').'.csv';

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
