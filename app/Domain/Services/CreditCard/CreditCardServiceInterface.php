<?php

declare(strict_types=1);

namespace App\Domain\Services\CreditCard;

use App\Domain\DTOs\CreditCard\CreditCardListRequestDTO;
use App\Models\Finance\CreditCard;

interface CreditCardServiceInterface
{
    public function getList(CreditCardListRequestDTO $dto): array;

    public function getById(int $id): CreditCard;

    public function getGatewayProfile(int $id): array;
}
