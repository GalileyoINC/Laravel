<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailTemplate;

use App\Domain\DTOs\EmailTemplate\EmailTemplateListRequestDTO;
use App\Domain\Services\EmailTemplate\EmailTemplateServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetEmailTemplateListAction
{
    public function __construct(
        private readonly EmailTemplateServiceInterface $emailTemplateService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = new EmailTemplateListRequestDTO(
                page: $data['page'] ?? 1,
                limit: $data['limit'] ?? 20,
                search: $data['search'] ?? null,
                status: $data['status'] ?? null
            );

            $templates = $this->emailTemplateService->getList($dto);

            return response()->json([
                'status' => 'success',
                'data' => $templates,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get email template list: '.$e->getMessage(),
            ], 500);
        }
    }
}
