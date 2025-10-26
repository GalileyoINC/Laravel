<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailTemplate;

use App\Domain\Services\EmailTemplate\EmailTemplateServiceInterface;
use Illuminate\Http\JsonResponse;

class GetEmailTemplateBodyAction
{
    public function __construct(
        private readonly EmailTemplateServiceInterface $emailTemplateService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $result = $this->emailTemplateService->getBody($data['id']);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
