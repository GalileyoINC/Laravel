<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailTemplate;

use App\Domain\DTOs\EmailTemplate\EmailTemplateUpdateRequestDTO;
use App\Domain\Services\EmailTemplate\EmailTemplateServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UpdateEmailTemplateAction
{
    public function __construct(
        private readonly EmailTemplateServiceInterface $emailTemplateService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $dto = new EmailTemplateUpdateRequestDTO(
                id: $data['id'],
                name: $data['name'] ?? null,
                subject: $data['subject'] ?? null,
                body: $data['body'] ?? null,
                params: $data['params'] ?? null,
                status: $data['status'] ?? null
            );

            $template = $this->emailTemplateService->update($dto);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Email template updated successfully',
                'data' => $template,
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update email template: '.$e->getMessage(),
            ], 500);
        }
    }
}
