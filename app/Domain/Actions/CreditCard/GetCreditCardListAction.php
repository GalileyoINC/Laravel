<?php

declare(strict_types=1);

namespace App\Domain\Actions\CreditCard;

use App\Domain\DTOs\CreditCard\CreditCardListRequestDTO;
use App\Domain\Services\CreditCard\CreditCardServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetCreditCardListAction
{
    public function __construct(
        private readonly CreditCardServiceInterface $creditCardService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        try {
            $dto = new CreditCardListRequestDTO(
                page: $data['page'] ?? 1,
                limit: $data['limit'] ?? 20,
                search: $data['search'] ?? null,
                user_id: $data['user_id'] ?? null,
                is_active: $data['is_active'] ?? null
            );

            $creditCards = $this->creditCardService->getList($dto);

            return response()->json([
                'status' => 'success',
                'data' => $creditCards,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get credit card list: '.$e->getMessage(),
            ], 500);
        }
    }
}
