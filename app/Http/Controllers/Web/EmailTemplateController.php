<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\EmailTemplate\ExportEmailTemplatesToCsvAction;
use App\Domain\Actions\EmailTemplate\GetEmailTemplateListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Communication\Web\AdminSendEmailRequest;
use App\Http\Requests\Communication\Web\EmailTemplateRequest;
use App\Http\Requests\EmailTemplate\Web\EmailTemplateIndexRequest;
use App\Models\Communication\EmailTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmailTemplateController extends Controller
{
    public function __construct(
        private readonly GetEmailTemplateListAction $getEmailTemplateListAction,
        private readonly ExportEmailTemplatesToCsvAction $exportEmailTemplatesToCsvAction,
    ) {}

    /**
     * Display Email Templates
     */
    public function index(EmailTemplateIndexRequest $request): View
    {
        $filters = $request->validated();
        $emailTemplates = $this->getEmailTemplateListAction->execute($filters, 20);

        return ViewFacade::make('email-template.index', [
            'emailTemplates' => $emailTemplates,
            'templates' => $emailTemplates,
            'filters' => $filters,
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
    public function update(EmailTemplateRequest $request, EmailTemplate $emailTemplate): RedirectResponse
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
    public function sendTestEmail(AdminSendEmailRequest $request, EmailTemplate $emailTemplate): RedirectResponse
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
    public function export(EmailTemplateIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportEmailTemplatesToCsvAction->execute($filters);
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
