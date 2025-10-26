<?php

declare(strict_types=1);

namespace App\Domain\Actions\EmailPool;

use App\Domain\Services\EmailPool\EmailPoolServiceInterface;
use Illuminate\Http\JsonResponse;

class GetEmailPoolAction
{
    public function __construct(
        private readonly EmailPoolServiceInterface $emailPoolService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $email = $this->emailPoolService->getById($data['id']);

        return response()->json([
            'status' => 'success',
            'data' => $email,
        ]);
    }
}
