<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\SmsSchedule\ExportSmsSchedulesToCsvAction;
use App\Domain\Actions\SmsSchedule\GetSmsScheduleListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SmsSchedule\Web\SmsScheduleIndexRequest;
use App\Models\Communication\SmsPool;
use App\Models\Communication\SmsShedule as SmsSchedule;
use App\Models\Subscription\Subscription;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SmsScheduleController extends Controller
{
    public function __construct(
        private readonly GetSmsScheduleListAction $getSmsScheduleListAction,
        private readonly ExportSmsSchedulesToCsvAction $exportSmsSchedulesToCsvAction,
    ) {}

    /**
     * Display SMS Schedules
     */
    public function index(SmsScheduleIndexRequest $request): View
    {
        $filters = $request->validated();
        $smsSchedules = $this->getSmsScheduleListAction->execute($filters, 20);

        // Get dropdown data
        $purposes = SmsPool::getPurposes();
        $statuses = SmsSchedule::getStatuses();
        $subscriptions = Subscription::getAllAsArray();

        return ViewFacade::make('sms-schedule.index', [
            'smsSchedules' => $smsSchedules,
            'purposes' => $purposes,
            'statuses' => $statuses,
            'subscriptions' => $subscriptions,
            'filters' => $filters,
        ]);
    }

    /**
     * Show SMS Schedule Details
     */
    public function show(SmsSchedule $smsSchedule): View
    {
        $smsSchedule->load(['user', 'staff', 'subscription', 'followerList', 'smsPool']);

        return ViewFacade::make('sms-schedule.show', [
            'smsSchedule' => $smsSchedule,
        ]);
    }

    /**
     * Export SMS Schedules to CSV
     */
    public function export(SmsScheduleIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportSmsSchedulesToCsvAction->execute($filters);
        $filename = 'sms_schedules_'.now()->format('Y-m-d_H-i-s').'.csv';

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
