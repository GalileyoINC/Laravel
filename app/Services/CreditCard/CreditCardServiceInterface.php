<?php

namespace App\Services\CreditCard;

use App\DTOs\CreditCard\CreditCardListRequestDTO;
use App\DTOs\CreditCard\CreditCardCreateRequestDTO;
use App\DTOs\CreditCard\CreditCardUpdateRequestDTO;
use App\DTOs\CreditCard\CreditCardIdRequestDTO;
use App\Models\User;

/**
 * CreditCard service interface
 */
interface CreditCardServiceInterface
{
    /**
     * Get credit cards for user
     *
     * @param CreditCardListRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function getCreditCardsForUser(CreditCardListRequestDTO $dto, User $user);

    /**
     * Create credit card
     *
     * @param CreditCardCreateRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function createCreditCard(CreditCardCreateRequestDTO $dto, User $user);

    /**
     * Update credit card
     *
     * @param CreditCardUpdateRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function updateCreditCard(CreditCardUpdateRequestDTO $dto, User $user);

    /**
     * Set preferred credit card
     *
     * @param CreditCardIdRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function setPreferredCreditCard(CreditCardIdRequestDTO $dto, User $user);

    /**
     * Delete credit card
     *
     * @param CreditCardIdRequestDTO $dto
     * @param User $user
     * @return mixed
     */
    public function deleteCreditCard(CreditCardIdRequestDTO $dto, User $user);
}
