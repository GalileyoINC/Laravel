<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Communication\EmailPool;
use App\Models\Communication\EmailPoolArchive;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class EmailPoolController extends Controller
{
    /**
     * Display Email Pools
     */
    public function index(Request $request): View
    {
        $query = EmailPool::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('from', 'like', "%{$search}%")
                    ->orWhere('to', 'like', "%{$search}%")
                    ->orWhere('reply', 'like', "%{$search}%")
                    ->orWhere('bcc', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $emailPools = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('email-pool.index', [
            'emailPools' => $emailPools,
            'filters' => $request->only(['search', 'type', 'status', 'created_at_from', 'created_at_to']),
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
    public function resend(Request $request, EmailPool $emailPool): Response
    {
        // Resend logic here
        $emailPool->update(['status' => EmailPool::STATUS_PENDING]);

        return redirect()->back()
            ->with('success', 'Email queued for resending successfully.');
    }

    /**
     * Delete Email Pool
     */
    public function destroy(EmailPool $emailPool): Response
    {
        $emailPool->delete();

        return redirect()->route('email-pool.index')
            ->with('success', 'Email pool deleted successfully.');
    }

    /**
     * Download Email Pool attachment
     */
    public function attachment(Request $request, $attachmentId): Response
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
    public function archive(Request $request): View
    {
        $query = EmailPoolArchive::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('from', 'like', "%{$search}%")
                    ->orWhere('to', 'like', "%{$search}%")
                    ->orWhere('reply', 'like', "%{$search}%")
                    ->orWhere('bcc', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $emailPools = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('email-pool.archive', [
            'emailPools' => $emailPools,
            'filters' => $request->only(['search', 'type', 'status', 'created_at_from', 'created_at_to']),
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
    public function export(Request $request): Response
    {
        $query = EmailPool::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('from', 'like', "%{$search}%")
                    ->orWhere('to', 'like', "%{$search}%")
                    ->orWhere('reply', 'like', "%{$search}%")
                    ->orWhere('bcc', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $emailPools = $query->orderBy('created_at', 'desc')->get();

        $csvData = [];
        $csvData[] = ['ID', 'Type', 'Status', 'From', 'To', 'Reply', 'BCC', 'Subject', 'Created At'];

        foreach ($emailPools as $emailPool) {
            $csvData[] = [
                $emailPool->id,
                $emailPool->type,
                $emailPool->status,
                $emailPool->from,
                $emailPool->to,
                $emailPool->reply,
                $emailPool->bcc,
                $emailPool->subject,
                $emailPool->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $filename = 'email_pools_'.now()->format('Y-m-d_H-i-s').'.csv';

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
