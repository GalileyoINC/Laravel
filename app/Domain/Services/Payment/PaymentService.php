<?php

declare(strict_types=1);

namespace App\Domain\Services\Payment;

use App\Models\CreditCard;
use App\Models\User\User;
use App\Domain\DTOs\Payment\PaymentDetailsDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * PaymentService
 * Service for payment operations
 */
class PaymentService implements PaymentServiceInterface
{
    public function getCreditCards(User $user, int $limit = 100, int $page = 1): array
    {
        $offset = ($page - 1) * $limit;
        
        $cards = $user->creditCards()
            ->active()
            ->orderBy('is_preferred', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get();

        $total = $user->creditCards()->active()->count();

        return [
            'count' => $total,
            'page' => $page,
            'page_size' => $limit,
            'list' => $cards->toArray(),
        ];
    }

    public function createCreditCard(User $user, PaymentDetailsDTO $dto): CreditCard
    {
        // Check if user already has active cards
        $activeCardsCount = $user->creditCards()->active()->count();
        
        // Mask card number for storage
        $maskedNumber = $this->maskCardNumber($dto->card_number);
        
        // Determine card type
        $cardType = $this->getCardType($dto->card_number);

        $card = $user->creditCards()->create([
            'first_name' => $dto->first_name,
            'last_name' => $dto->last_name,
            'phone' => $dto->phone,
            'num' => $maskedNumber,
            'cvv' => encrypt($dto->security_code), // Encrypt CVV
            'type' => $cardType,
            'expiration_year' => (int) $dto->expiration_year,
            'expiration_month' => (int) $dto->expiration_month,
            'zip' => $dto->zip,
            'is_active' => true,
            'is_preferred' => $activeCardsCount === 0, // First card is preferred
            'is_agree_to_receive' => $dto->is_agree_to_receive,
        ]);

        return $card;
    }

    public function updateCreditCard(User $user, PaymentDetailsDTO $dto): CreditCard
    {
        $card = $user->creditCards()->findOrFail($dto->id);
        
        // Mask card number for storage
        $maskedNumber = $this->maskCardNumber($dto->card_number);
        
        // Determine card type
        $cardType = $this->getCardType($dto->card_number);

        $card->update([
            'first_name' => $dto->first_name,
            'last_name' => $dto->last_name,
            'phone' => $dto->phone,
            'num' => $maskedNumber,
            'cvv' => encrypt($dto->security_code), // Encrypt CVV
            'type' => $cardType,
            'expiration_year' => (int) $dto->expiration_year,
            'expiration_month' => (int) $dto->expiration_month,
            'zip' => $dto->zip,
            'is_agree_to_receive' => $dto->is_agree_to_receive,
        ]);

        return $card->fresh();
    }

    public function setPreferredCard(User $user, int $cardId): bool
    {
        $card = $user->creditCards()->findOrFail($cardId);
        
        if (!$card->is_active) {
            return false;
        }

        DB::transaction(function () use ($user, $card) {
            // Remove preferred status from all cards
            $user->creditCards()->update(['is_preferred' => false]);
            
            // Set this card as preferred
            $card->update(['is_preferred' => true]);
        });

        return true;
    }

    public function deleteCreditCard(User $user, int $cardId): bool
    {
        $card = $user->creditCards()->findOrFail($cardId);
        
        // Check if user has more than one active card
        $activeCardsCount = $user->creditCards()->active()->count();
        
        if ($activeCardsCount <= 1) {
            throw new \Exception('To remove, add another payment method first');
        }

        // If deleting preferred card, set another card as preferred
        if ($card->is_preferred) {
            $nextCard = $user->creditCards()
                ->active()
                ->where('id', '!=', $cardId)
                ->first();
                
            if ($nextCard) {
                $nextCard->update(['is_preferred' => true]);
            }
        }

        $card->update(['is_active' => false]);
        
        return true;
    }

    /**
     * Mask card number for display
     */
    private function maskCardNumber(string $cardNumber): string
    {
        if (strlen($cardNumber) <= 4) {
            return $cardNumber;
        }
        
        return str_repeat('*', strlen($cardNumber) - 4) . substr($cardNumber, -4);
    }

    /**
     * Determine card type from number
     */
    private function getCardType(string $cardNumber): string
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        
        if (preg_match('/^4/', $cardNumber)) {
            return 'Visa';
        } elseif (preg_match('/^5[1-5]/', $cardNumber)) {
            return 'MasterCard';
        } elseif (preg_match('/^3[47]/', $cardNumber)) {
            return 'American Express';
        } elseif (preg_match('/^6/', $cardNumber)) {
            return 'Discover';
        }
        
        return 'Unknown';
    }
}
