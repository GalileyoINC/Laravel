<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailTemplate;

use App\Domain\DTOs\EmailTemplate\EmailTemplateSendRequestDTO;
use App\Domain\Services\EmailTemplate\EmailTemplateServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SendAdminEmailAction
{
    public function __construct(
        private readonly EmailTemplateServiceInterface $emailTemplateService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $dto = new EmailTemplateSendRequestDTO(
                id: $data['id'],
                to: $data['to'],
                subject: $data['subject'] ?? null,
                body: $data['body'] ?? null,
                params: $data['params'] ?? []
            );

            $result = $this->emailTemplateService->sendAdminEmail($dto);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Email sent successfully',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send email: '.$e->getMessage(),
            ], 500);
        }
    }
}
