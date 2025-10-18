<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Communication\EmailPool;
use App\Models\Communication\EmailPoolArchive;
use App\Models\Communication\EmailPoolAttachment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class EmailPoolArchiveController extends Controller
{
    /**
     * Display Email Pool Archive
     */
    public function index(Request $request): View
    {
        $query = EmailPoolArchive::with(['attachments']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('to', 'like', "%{$search}%")
                    ->orWhere('from', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
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

        // Filter by from
        if ($request->filled('from')) {
            $query->where('from', 'like', "%{$request->get('from')}%");
        }

        // Filter by to
        if ($request->filled('to')) {
            $query->where('to', 'like', "%{$request->get('to')}%");
        }

        // Filter by subject
        if ($request->filled('subject')) {
            $query->where('subject', 'like', "%{$request->get('subject')}%");
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        if ($request->filled('updated_at_from')) {
            $query->whereDate('updated_at', '>=', $request->get('updated_at_from'));
        }
        if ($request->filled('updated_at_to')) {
            $query->whereDate('updated_at', '<=', $request->get('updated_at_to'));
        }

        $emailPoolArchives = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get dropdown data
        $sendingTypes = EmailPool::getSendingTypes();
        $statuses = EmailPool::getStatuses();

        return ViewFacade::make('email-pool-archive.index', [
            'emailPoolArchives' => $emailPoolArchives,
            'sendingTypes' => $sendingTypes,
            'statuses' => $statuses,
            'filters' => $request->only(['search', 'type', 'status', 'from', 'to', 'subject', 'created_at_from', 'created_at_to', 'updated_at_from', 'updated_at_to']),
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
            ->header('Content-Type', $attachment->content_type)
            ->header('Content-Disposition', 'attachment; filename="'.$attachment->file_name.'"');
    }

    /**
     * Delete Email Pool Archive
     */
    public function destroy(EmailPoolArchive $emailPoolArchive): Response
    {
            $emailPoolArchive->delete();

            return redirect()->route('email-pool-archive.index')
                ->with('success', 'Email pool archive deleted successfully.');
    }

    /**
     * Export Email Pool Archive to CSV
     */
    public function export(Request $request): Response
    {
            $query = EmailPoolArchive::with(['attachments']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('to', 'like', "%{$search}%")
                        ->orWhere('from', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                });
            }

            if ($request->filled('type')) {
                $query->where('type', $request->get('type'));
            }

            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }

            if ($request->filled('from')) {
                $query->where('from', 'like', "%{$request->get('from')}%");
            }

            if ($request->filled('to')) {
                $query->where('to', 'like', "%{$request->get('to')}%");
            }

            if ($request->filled('subject')) {
                $query->where('subject', 'like', "%{$request->get('subject')}%");
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            if ($request->filled('updated_at_from')) {
                $query->whereDate('updated_at', '>=', $request->get('updated_at_from'));
            }
            if ($request->filled('updated_at_to')) {
                $query->whereDate('updated_at', '<=', $request->get('updated_at_to'));
            }

            $emailPoolArchives = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Type', 'Status', 'From', 'To', 'Subject', 'Created At', 'Updated At'];

            foreach ($emailPoolArchives as $emailPoolArchive) {
                $csvData[] = [
                    $emailPoolArchive->id,
                    EmailPool::getSendingTypes()[$emailPoolArchive->type] ?? $emailPoolArchive->type,
                    EmailPool::getStatuses()[$emailPoolArchive->status] ?? $emailPoolArchive->status,
                    $emailPoolArchive->from,
                    $emailPoolArchive->to,
                    $emailPoolArchive->subject,
                    $emailPoolArchive->created_at->format('Y-m-d H:i:s'),
                    $emailPoolArchive->updated_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'email_pool_archive_'.now()->format('Y-m-d_H-i-s').'.csv';

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
