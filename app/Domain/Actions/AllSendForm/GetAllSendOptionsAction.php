<?php

declare(strict_types=1);

namespace App\Domain\Actions\AllSendForm;

use App\Domain\DTOs\AllSendForm\AllSendOptionsRequestDTO;
use App\Domain\Services\AllSendForm\AllSendFormServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetAllSendOptionsAction
{
    public function __construct(
        private readonly AllSendFormServiceInterface $allSendFormService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = AllSendOptionsRequestDTO::fromArray($data);
            $user = Auth::user();

            $options = $this->allSendFormService->getAllSendOptions($dto, $user);

            return response()->json($options);

        } catch (Exception $e) {
            Log::error('GetAllSendOptionsAction error: '.$e->getMessage());

            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500,
            ], 500);
        }
    }
}
