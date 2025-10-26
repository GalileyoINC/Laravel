<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailPool;

use App\Domain\Services\EmailPool\EmailPoolServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DeleteEmailPoolAction
{
    public function __construct(
        private readonly EmailPoolServiceInterface $emailPoolService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        DB::beginTransaction();

        $this->emailPoolService->delete($data['id']);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Email deleted successfully',
        ]);
    }
}
