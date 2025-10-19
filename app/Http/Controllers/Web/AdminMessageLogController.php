<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\AdminMessageLog\ExportAdminMessageLogsToCsvAction;
use App\Domain\Actions\AdminMessageLog\GetAdminMessageLogListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminMessageLog\Web\AdminMessageLogRequest;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminMessageLogController extends Controller
{
    public function __construct(
        private readonly GetAdminMessageLogListAction $getAdminMessageLogListAction,
        private readonly ExportAdminMessageLogsToCsvAction $exportAdminMessageLogsToCsvAction,
    ) {}

    /**
     * Display Admin Message Logs
     */
    public function index(AdminMessageLogRequest $request): View
    {
        $filters = $request->validated();
        $adminMessageLogs = $this->getAdminMessageLogListAction->execute($filters, 20);

        return ViewFacade::make('admin-message-log.index', [
            'adminMessageLogs' => $adminMessageLogs,
            'filters' => $filters,
        ]);
    }

    /**
     * Export Admin Message Logs to CSV
     */
    public function export(AdminMessageLogRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $rows = $this->exportAdminMessageLogsToCsvAction->execute($filters);

        $filename = 'admin_message_logs_'.now()->format('Y-m-d_H-i-s').'.csv';

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
