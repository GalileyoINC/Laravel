<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\SmsPoolArchive\ExportSmsPoolArchiveToCsvAction;
use App\Domain\Actions\SmsPoolArchive\GetSmsPoolArchiveListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SmsPool\Web\SmsPoolArchiveIndexRequest;
use App\Models\Communication\SmsPool;
use App\Models\Communication\SmsPoolArchive;
use App\Models\Subscription\Subscription;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SmsPoolArchiveController extends Controller
{
    public function __construct(
        private readonly GetSmsPoolArchiveListAction $getSmsPoolArchiveListAction,
        private readonly ExportSmsPoolArchiveToCsvAction $exportSmsPoolArchiveToCsvAction,
    ) {}

    /**
     * Display SMS Pool Archive
     */
    public function index(SmsPoolArchiveIndexRequest $request): View
    {
        $filters = $request->validated();
        $smsPoolArchives = $this->getSmsPoolArchiveListAction->execute($filters, 20);

        // Get dropdown data
        $purposes = SmsPool::getPurposes();
        $subscriptions = Subscription::getForDropDown();

        return ViewFacade::make('sms-pool-archive.index', [
            'smsPoolArchives' => $smsPoolArchives,
            'purposes' => $purposes,
            'subscriptions' => $subscriptions,
            'filters' => $filters,
        ]);
    }

    /**
     * Show SMS Pool Archive Details
     */
    public function show(SmsPoolArchive $smsPoolArchive): View
    {
        $smsPoolArchive->load(['user', 'staff', 'subscription', 'followerList']);

        return ViewFacade::make('sms-pool-archive.show', [
            'smsPoolArchive' => $smsPoolArchive,
        ]);
    }

    /**
     * Export SMS Pool Archive to CSV
     */
    public function export(SmsPoolArchiveIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportSmsPoolArchiveToCsvAction->execute($filters);
        $filename = 'sms_pool_archive_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
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
