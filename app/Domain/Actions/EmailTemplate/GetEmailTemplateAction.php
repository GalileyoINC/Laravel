<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailTemplate;

use App\Domain\Services\EmailTemplate\EmailTemplateServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetEmailTemplateAction
{
    public function __construct(
        private readonly EmailTemplateServiceInterface $emailTemplateService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $template = $this->emailTemplateService->getById($data['id']);

            return response()->json([
                'status' => 'success',
                'data' => $template,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get email template: '.$e->getMessage(),
            ], 500);
        }
    }
}
