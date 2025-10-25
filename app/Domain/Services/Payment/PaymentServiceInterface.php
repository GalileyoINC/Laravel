<?php

declare(strict_types=1);

namespace App\Domain\Services\Payment;

use App\Models\CreditCard;
use App\Models\User\User;
use App\Domain\DTOs\Payment\PaymentDetailsDTO;
use Illuminate\Database\Eloquent\Collection;

/**
 * PaymentServiceInterface
 * Interface for payment service operations
 */
interface PaymentServiceInterface
{
    public function getCreditCards(User $user, int $limit = 100, int $page = 1): array;
    public function createCreditCard(User $user, PaymentDetailsDTO $dto): CreditCard;
    public function updateCreditCard(User $user, PaymentDetailsDTO $dto): CreditCard;
    public function setPreferredCard(User $user, int $cardId): bool;
    public function deleteCreditCard(User $user, int $cardId): bool;
}
