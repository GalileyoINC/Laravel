<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailPool;

use App\Domain\DTOs\EmailPool\EmailPoolListRequestDTO;
use App\Domain\Services\EmailPool\EmailPoolServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetEmailPoolListAction
{
    public function __construct(
        private readonly EmailPoolServiceInterface $emailPoolService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = new EmailPoolListRequestDTO(
                page: $data['page'] ?? 1,
                limit: $data['limit'] ?? 20,
                search: $data['search'] ?? null,
                status: $data['status'] ?? null,
                to: $data['to'] ?? null
            );

            $emails = $this->emailPoolService->getList($dto);

            return response()->json([
                'status' => 'success',
                'data' => $emails,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get email pool list: '.$e->getMessage(),
            ], 500);
        }
    }
}
