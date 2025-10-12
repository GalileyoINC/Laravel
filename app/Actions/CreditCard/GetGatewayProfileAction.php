<?php

declare(strict_types=1);

namespace App\Actions\CreditCard;

use App\Services\CreditCard\CreditCardServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetGatewayProfileAction
{
    public function __construct(
        private readonly CreditCardServiceInterface $creditCardService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $result = $this->creditCardService->getGatewayProfile($data['id']);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get gateway profile: '.$e->getMessage(),
            ], 500);
        }
    }
}
