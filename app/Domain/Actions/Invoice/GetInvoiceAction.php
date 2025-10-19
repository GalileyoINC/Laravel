<?php

declare(strict_types=1);

namespace App\Domain\Actions\Invoice;

use App\Models\Finance\Invoice;

final class GetInvoiceAction
{
    public function execute(int $id): Invoice
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::with(['user', 'invoiceLines.bundle', 'moneyTransactions.creditCard'])
            ->findOrFail($id);

        return $invoice;
    }
}
