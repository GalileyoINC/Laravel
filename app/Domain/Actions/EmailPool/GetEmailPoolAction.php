<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailPool;

use App\Domain\Services\EmailPool\EmailPoolServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetEmailPoolAction
{
    public function __construct(
        private readonly EmailPoolServiceInterface $emailPoolService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $email = $this->emailPoolService->getById($data['id']);

            return response()->json([
                'status' => 'success',
                'data' => $email,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get email: '.$e->getMessage(),
            ], 500);
        }
    }
}
