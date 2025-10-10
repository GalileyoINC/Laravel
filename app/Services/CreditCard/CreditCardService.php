<?php

namespace App\Services\CreditCard;

use App\DTOs\CreditCard\CreditCardListRequestDTO;
use App\DTOs\CreditCard\CreditCardCreateRequestDTO;
use App\DTOs\CreditCard\CreditCardUpdateRequestDTO;
use App\DTOs\CreditCard\CreditCardIdRequestDTO;
use App\Models\User;
use App\Models\CreditCard;
use Illuminate\Support\Facades\Log;

/**
 * CreditCard service implementation
 */
class CreditCardService implements CreditCardServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCreditCardsForUser(CreditCardListRequestDTO $dto, User $user)
    {
        try {
            $query = CreditCard::where('id_user', $user->id);

            // Apply filters if any
            if (!empty($dto->filter)) {
                // Add filter logic here
            }

            $creditCards = $query->orderBy('is_preferred', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit($dto->limit)
                ->offset($dto->offset)
                ->get();

            return $creditCards;

        } catch (\Exception $e) {
            Log::error('CreditCardService getCreditCardsForUser error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createCreditCard(CreditCardCreateRequestDTO $dto, User $user)
    {
        try {
            // Mask the card number for storage (show only last 4 digits)
            $maskedNum = '**** **** **** ' . substr($dto->num, -4);

            $creditCard = CreditCard::create([
                'id_user' => $user->id,
                'first_name' => $dto->firstName,
                'last_name' => $dto->lastName,
                'num' => $maskedNum,
                'phone' => $dto->phone,
                'zip' => $dto->zip,
                'cvv' => $dto->cvv,
                'expiration_year' => $dto->expirationYear,
                'expiration_month' => $dto->expirationMonth,
                'is_agree_to_receive' => $dto->isAgreeToReceive,
                'is_preferred' => false, // New cards are not preferred by default
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return $creditCard;

        } catch (\Exception $e) {
            Log::error('CreditCardService createCreditCard error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateCreditCard(CreditCardUpdateRequestDTO $dto, User $user)
    {
        try {
            $creditCard = CreditCard::where('id', $dto->id)
                ->where('id_user', $user->id)
                ->first();

            if (!$creditCard) {
                throw new \Exception('Credit card not found or access denied');
            }

            $updateData = [];
            
            if ($dto->firstName !== null) $updateData['first_name'] = $dto->firstName;
            if ($dto->lastName !== null) $updateData['last_name'] = $dto->lastName;
            if ($dto->phone !== null) $updateData['phone'] = $dto->phone;
            if ($dto->zip !== null) $updateData['zip'] = $dto->zip;
            if ($dto->cvv !== null) $updateData['cvv'] = $dto->cvv;
            if ($dto->expirationYear !== null) $updateData['expiration_year'] = $dto->expirationYear;
            if ($dto->expirationMonth !== null) $updateData['expiration_month'] = $dto->expirationMonth;
            if ($dto->isPreferred !== null) $updateData['is_preferred'] = $dto->isPreferred;
            if ($dto->isAgreeToReceive !== null) $updateData['is_agree_to_receive'] = $dto->isAgreeToReceive;

            $updateData['updated_at'] = now();

            $creditCard->update($updateData);

            return $creditCard;

        } catch (\Exception $e) {
            Log::error('CreditCardService updateCreditCard error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPreferredCreditCard(CreditCardIdRequestDTO $dto, User $user)
    {
        try {
            // First, unset all preferred cards for this user
            CreditCard::where('id_user', $user->id)->update(['is_preferred' => false]);

            // Then set the selected card as preferred
            $creditCard = CreditCard::where('id', $dto->id)
                ->where('id_user', $user->id)
                ->first();

            if (!$creditCard) {
                throw new \Exception('Credit card not found or access denied');
            }

            $creditCard->update([
                'is_preferred' => true,
                'updated_at' => now()
            ]);

            return $creditCard;

        } catch (\Exception $e) {
            Log::error('CreditCardService setPreferredCreditCard error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteCreditCard(CreditCardIdRequestDTO $dto, User $user)
    {
        try {
            $creditCard = CreditCard::where('id', $dto->id)
                ->where('id_user', $user->id)
                ->first();

            if (!$creditCard) {
                throw new \Exception('Credit card not found or access denied');
            }

            $creditCard->delete();

            return ['success' => true, 'message' => 'Credit card deleted successfully'];

        } catch (\Exception $e) {
            Log::error('CreditCardService deleteCreditCard error: ' . $e->getMessage());
            throw $e;
        }
    }
}
