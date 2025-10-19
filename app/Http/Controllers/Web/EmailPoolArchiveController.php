<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\EmailPoolArchive\ExportEmailPoolArchiveToCsvAction;
use App\Domain\Actions\EmailPoolArchive\GetEmailPoolArchiveListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailPoolArchive\Web\EmailPoolArchiveIndexRequest;
use App\Models\Communication\EmailPool;
use App\Models\Communication\EmailPoolArchive;
use App\Models\Communication\EmailPoolAttachment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmailPoolArchiveController extends Controller
{
    public function __construct(
        private readonly GetEmailPoolArchiveListAction $getEmailPoolArchiveListAction,
        private readonly ExportEmailPoolArchiveToCsvAction $exportEmailPoolArchiveToCsvAction,
    ) {}

    /**
     * Display Email Pool Archive
     */
    public function index(EmailPoolArchiveIndexRequest $request): View
    {
        $filters = $request->validated();
        $emailPoolArchives = $this->getEmailPoolArchiveListAction->execute($filters, 20);

        // Get dropdown data
        $sendingTypes = EmailPool::getSendingTypes();
        $statuses = EmailPool::getStatuses();

        return ViewFacade::make('email-pool-archive.index', [
            'emailPoolArchives' => $emailPoolArchives,
            'sendingTypes' => $sendingTypes,
            'statuses' => $statuses,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Email Pool Archive Details
     */
    public function show(EmailPoolArchive $emailPoolArchive): View
    {
        $emailPoolArchive->load(['attachments']);

        return ViewFacade::make('email-pool-archive.show', [
            'emailPoolArchive' => $emailPoolArchive,
        ]);
    }

    /**
     * View Email Body
     */
    public function viewBody(EmailPoolArchive $emailPoolArchive): Response
    {
        return response($emailPoolArchive->body)
            ->header('Content-Type', 'text/html');
    }

    /**
     * Download Attachment
     */
    public function attachment(EmailPoolAttachment $attachment): Response
    {
        return response($attachment->body)
            ->header('Content-Type', $attachment->content_type ?? 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="'.($attachment->file_name ?? 'attachment').'"');
    }

    /**
     * Delete Email Pool Archive
     */
    public function destroy(EmailPoolArchive $emailPoolArchive): RedirectResponse
    {
        $emailPoolArchive->delete();

        return redirect()->route('email-pool-archive.index')
            ->with('success', 'Email pool archive deleted successfully.');
    }

    /**
     * Export Email Pool Archive to CSV
     */
    public function export(EmailPoolArchiveIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportEmailPoolArchiveToCsvAction->execute($filters);

        $filename = 'email_pool_archive_'.now()->format('Y-m-d_H-i-s').'.csv';

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
