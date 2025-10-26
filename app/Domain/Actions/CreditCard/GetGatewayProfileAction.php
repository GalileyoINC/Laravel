<?php

declare(strict_types=1);

namespace App\Domain\Actions\CreditCard;

use App\Domain\Services\CreditCard\CreditCardServiceInterface;
use Illuminate\Http\JsonResponse;

class GetGatewayProfileAction
{
    public function __construct(
        private readonly CreditCardServiceInterface $creditCardService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $result = $this->creditCardService->getGatewayProfile($data['id']);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
