<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailPool;

use App\Domain\Services\EmailPool\EmailPoolServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ResendEmailAction
{
    public function __construct(
        private readonly EmailPoolServiceInterface $emailPoolService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $result = $this->emailPoolService->resend($data['id']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Email resent successfully',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resend email: '.$e->getMessage(),
            ], 500);
        }
    }
}
