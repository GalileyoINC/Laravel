<?php

declare(strict_types=1);

namespace App\Domain\Actions\AllSendForm;

use App\Domain\DTOs\AllSendForm\AllSendOptionsRequestDTO;
use App\Domain\Services\AllSendForm\AllSendFormServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

readonly class GetAllSendOptionsAction
{
    public function __construct(
        private AllSendFormServiceInterface $allSendFormService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $dto = AllSendOptionsRequestDTO::fromArray($data);
        $user = Auth::user();

        $options = $this->allSendFormService->getAllSendOptions($dto, $user);

        return response()->json($options);
    }
}
