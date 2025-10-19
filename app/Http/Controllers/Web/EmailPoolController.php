<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\EmailPool\ExportEmailPoolsToCsvAction;
use App\Domain\Actions\EmailPool\GetEmailPoolListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailPool\Web\EmailPoolIndexRequest;
use App\Models\Communication\EmailPool;
use App\Models\Communication\EmailPoolArchive;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmailPoolController extends Controller
{
    public function __construct(
        private readonly GetEmailPoolListAction $getEmailPoolListAction,
        private readonly ExportEmailPoolsToCsvAction $exportEmailPoolsToCsvAction,
    ) {}

    /**
     * Display Email Pools
     */
    public function index(EmailPoolIndexRequest $request): View
    {
        $filters = $request->validated();
        $payload = array_merge($filters, [
            'page' => (int) ($filters['page'] ?? 1),
            'limit' => 20,
        ]);

        $response = $this->getEmailPoolListAction->execute($payload);
        $data = $response->getData(true);
        $emailPools = $data['data'] ?? [];

        return ViewFacade::make('email-pool.index', [
            'emailPools' => $emailPools,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Email Pool details
     */
    public function show(EmailPool $emailPool): View
    {
        return ViewFacade::make('email-pool.show', [
            'emailPool' => $emailPool,
        ]);
    }

    /**
     * Show Email Pool body in iframe
     */
    public function viewBody(EmailPool $emailPool): View
    {
        return ViewFacade::make('email-pool.view-body', [
            'emailPool' => $emailPool,
        ]);
    }

    /**
     * Resend Email Pool
     */
    public function resend(Request $request, EmailPool $emailPool): RedirectResponse
    {
        // Resend logic here
        $emailPool->update(['status' => EmailPool::STATUS_PENDING]);

        return redirect()->back()
            ->with('success', 'Email queued for resending successfully.');
    }

    /**
     * Delete Email Pool
     */
    public function destroy(EmailPool $emailPool): RedirectResponse
    {
        $emailPool->delete();

        return redirect()->route('email-pool.index')
            ->with('success', 'Email pool deleted successfully.');
    }

    /**
     * Download Email Pool attachment
     */
    public function attachment(Request $request, int $attachmentId): Response
    {
        // Get attachment logic here
        // For now, return a placeholder response
        return response()->json([
            'message' => 'Attachment download functionality would be implemented here',
            'attachment_id' => $attachmentId,
        ]);
    }

    /**
     * Display Email Pool Archive
     */
    public function archive(EmailPoolIndexRequest $request): View
    {
        $filters = $request->validated();
        $query = EmailPoolArchive::query();
        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('from', 'like', "%{$search}%")
                    ->orWhere('to', 'like', "%{$search}%")
                    ->orWhere('reply', 'like', "%{$search}%")
                    ->orWhere('bcc', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        if (! empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }
        $emailPools = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('email-pool.archive', [
            'emailPools' => $emailPools,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Email Pool Archive details
     */
    public function showArchive(EmailPoolArchive $emailPool): View
    {
        return ViewFacade::make('email-pool.archive-show', [
            'emailPool' => $emailPool,
        ]);
    }

    /**
     * Export Email Pools to CSV
     */
    public function export(EmailPoolIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportEmailPoolsToCsvAction->execute($filters);
        $filename = 'email_pools_'.now()->format('Y-m-d_H-i-s').'.csv';

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
