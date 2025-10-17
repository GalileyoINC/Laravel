<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailPool;

use App\Domain\Services\EmailPool\EmailPoolServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DeleteEmailPoolAction
{
    public function __construct(
        private readonly EmailPoolServiceInterface $emailPoolService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $this->emailPoolService->delete($data['id']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Email deleted successfully',
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete email: '.$e->getMessage(),
            ], 500);
        }
    }
}
