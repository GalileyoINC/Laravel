<?php

declare(strict_types=1);

namespace App\Actions\EmailPool;

use App\Services\EmailPool\EmailPoolServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetEmailAttachmentAction
{
    public function __construct(
        private readonly EmailPoolServiceInterface $emailPoolService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $attachment = $this->emailPoolService->getAttachment($data['id']);

            return response()->json([
                'status' => 'success',
                'data' => $attachment,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get attachment: '.$e->getMessage(),
            ], 500);
        }
    }
}
