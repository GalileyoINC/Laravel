<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Communication\Web\AdminSendEmailRequest;
use App\Http\Requests\Communication\Web\EmailTemplateRequest;
use App\Models\Communication\EmailTemplate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class EmailTemplateController extends Controller
{
    /**
     * Display Email Templates
     */
    public function index(Request $request): View
    {
        $query = EmailTemplate::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('from', 'like', "%{$search}%");
            });
        }

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }

        // Filter by subject
        if ($request->filled('subject')) {
            $query->where('subject', 'like', "%{$request->get('subject')}%");
        }

        // Filter by from
        if ($request->filled('from')) {
            $query->where('from', 'like', "%{$request->get('from')}%");
        }

        // Filter by date range
        if ($request->filled('created_at_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_at_from'));
        }
        if ($request->filled('created_at_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_at_to'));
        }

        $emailTemplates = $query->orderBy('created_at', 'desc')->paginate(20);

        return ViewFacade::make('email-template.index', [
            'emailTemplates' => $emailTemplates,
            'templates' => $emailTemplates,
            'filters' => $request->only(['search', 'name', 'subject', 'from', 'created_at_from', 'created_at_to']),
        ]);
    }

    /**
     * Show Email Template Details
     */
    public function show(EmailTemplate $emailTemplate): View
    {
        return ViewFacade::make('email-template.show', [
            'emailTemplate' => $emailTemplate,
        ]);
    }

    /**
     * Show Email Template Edit Form
     */
    public function edit(EmailTemplate $emailTemplate): View
    {
        return ViewFacade::make('email-template.edit', [
            'emailTemplate' => $emailTemplate,
        ]);
    }

    /**
     * Update Email Template
     */
    public function update(EmailTemplateRequest $request, EmailTemplate $emailTemplate): Response
    {
            $emailTemplate->update($request->validated());

            return redirect()->route('email-template.show', $emailTemplate)
                ->with('success', 'Email template updated successfully.');
    }

    /**
     * View Email Template Body
     */
    public function viewBody(EmailTemplate $emailTemplate): View
    {
        // Get example values for parameters
        $params = [];
        if ($emailTemplate->params) {
            foreach ($emailTemplate->params as $param) {
                $params[$param['name']] = $param['example'] ?? '';
            }
        }

        // Replace placeholders in body
        $content = $emailTemplate->placeBody($params);

        return ViewFacade::make('email-template.view-body', [
            'content' => $content,
        ]);
    }

    /**
     * Show Admin Send Email Form
     */
    public function adminSend(EmailTemplate $emailTemplate): View
    {
        return ViewFacade::make('email-template.admin-send', [
            'emailTemplate' => $emailTemplate,
        ]);
    }

    /**
     * Send Test Email
     */
    public function sendTestEmail(AdminSendEmailRequest $request, EmailTemplate $emailTemplate): Response
    {
            // Here you would implement the actual email sending logic
            // For now, we'll just simulate it

            $emailData = [
                'to' => $request->get('email'),
                'template' => $emailTemplate,
                'params' => $emailTemplate->params ? array_map(fn ($param) => $param['example'] ?? '', $emailTemplate->params) : [],
            ];

            // Simulate email sending
            // Mail::send(new EmailTemplateMail($emailTemplate, $emailData));

            return redirect()->route('email-pool.index')
                ->with('success', 'Test email sent successfully.');
    }

    /**
     * Export Email Templates to CSV
     */
    public function export(Request $request): Response
    {
            $query = EmailTemplate::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('from', 'like', "%{$search}%");
                });
            }

            if ($request->filled('name')) {
                $query->where('name', 'like', "%{$request->get('name')}%");
            }

            if ($request->filled('subject')) {
                $query->where('subject', 'like', "%{$request->get('subject')}%");
            }

            if ($request->filled('from')) {
                $query->where('from', 'like', "%{$request->get('from')}%");
            }

            if ($request->filled('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->get('created_at_from'));
            }
            if ($request->filled('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->get('created_at_to'));
            }

            $emailTemplates = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['ID', 'Name', 'Subject', 'From', 'Created At'];

            foreach ($emailTemplates as $emailTemplate) {
                $csvData[] = [
                    $emailTemplate->id,
                    $emailTemplate->name,
                    $emailTemplate->subject,
                    $emailTemplate->from,
                    $emailTemplate->created_at->format('Y-m-d H:i:s'),
                ];
            }

            $filename = 'email_templates_'.now()->format('Y-m-d_H-i-s').'.csv';

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
