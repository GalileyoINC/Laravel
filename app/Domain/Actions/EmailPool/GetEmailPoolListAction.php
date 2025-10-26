<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailPool;

use App\Domain\Services\EmailPool\EmailPoolServiceInterface;
use Illuminate\Http\JsonResponse;

class GetEmailPoolListAction
{
    public function __construct(
        private readonly EmailPoolServiceInterface $emailPoolService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $page = $data['page'] ?? 1;
        $limit = $data['limit'] ?? 20;
        $search = $data['search'] ?? null;
        $status = $data['status'] ?? null;
        $to = $data['to'] ?? null;

        $emails = $this->emailPoolService->getList($page, $limit, $search, $status, $to);

        return response()->json([
            'status' => 'success',
            'data' => $emails,
        ]);
    }
}
