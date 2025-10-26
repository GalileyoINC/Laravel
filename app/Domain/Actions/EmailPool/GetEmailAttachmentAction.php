<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailPool;

use App\Domain\Services\EmailPool\EmailPoolServiceInterface;
use Illuminate\Http\JsonResponse;

class GetEmailAttachmentAction
{
    public function __construct(
        private readonly EmailPoolServiceInterface $emailPoolService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $attachment = $this->emailPoolService->getAttachment($data['id']);

        return response()->json([
            'status' => 'success',
            'data' => $attachment,
        ]);
    }
}
