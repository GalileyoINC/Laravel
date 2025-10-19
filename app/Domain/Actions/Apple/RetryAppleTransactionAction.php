<?php

declare(strict_types=1);

namespace App\Domain\Actions\Apple;

use App\Models\Order\AppleAppTransaction;

final class RetryAppleTransactionAction
{
    public function execute(int $transactionId): bool
    {
        /** @var AppleAppTransaction $transaction */
        $transaction = AppleAppTransaction::findOrFail($transactionId);
        // Domain retry logic placeholder
        $transaction->update(['is_process' => false]);

        return true;
    }
}
