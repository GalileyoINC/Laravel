<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentDetailsRequest;
use App\Http\Requests\Payment\PaymentListRequest;
use App\Domain\DTOs\Payment\PaymentDetailsDTO;
use App\Domain\DTOs\Payment\PaymentListRequestDTO;
use App\Domain\Actions\Payment\CreateCreditCardAction;
use App\Domain\Actions\Payment\GetCreditCardsAction;
use App\Domain\Actions\Payment\UpdateCreditCardAction;
use App\Domain\Actions\Payment\SetPreferredCardAction;
use App\Domain\Actions\Payment\DeleteCreditCardAction;
use Illuminate\Http\JsonResponse;

/**
 * PaymentController
 * Controller for payment-related operations
 */
class PaymentController extends Controller
{
    public function __construct(
        private readonly CreateCreditCardAction $createCreditCardAction,
        private readonly GetCreditCardsAction $getCreditCardsAction,
        private readonly UpdateCreditCardAction $updateCreditCardAction,
        private readonly SetPreferredCardAction $setPreferredCardAction,
        private readonly DeleteCreditCardAction $deleteCreditCardAction,
    ) {}

    /**
     * Get user's credit cards
     */
    public function getCreditCards(PaymentListRequest $request): JsonResponse
    {
        $dto = PaymentListRequestDTO::fromRequest($request);
        return $this->getCreditCardsAction->execute($dto);
    }

    /**
     * Create a new credit card
     */
    public function createCreditCard(PaymentDetailsRequest $request): JsonResponse
    {
        $dto = PaymentDetailsDTO::fromRequest($request);
        return $this->createCreditCardAction->execute($dto);
    }

    /**
     * Update a credit card
     */
    public function updateCreditCard(PaymentDetailsRequest $request): JsonResponse
    {
        $dto = PaymentDetailsDTO::fromRequest($request);
        return $this->updateCreditCardAction->execute($dto);
    }

    /**
     * Set a credit card as preferred
     */
    public function setPreferredCard(PaymentDetailsRequest $request): JsonResponse
    {
        $cardId = $request->input('id');
        return $this->setPreferredCardAction->execute($cardId);
    }

    /**
     * Delete a credit card
     */
    public function deleteCreditCard(PaymentDetailsRequest $request): JsonResponse
    {
        $cardId = $request->input('id');
        return $this->deleteCreditCardAction->execute($cardId);
    }
}