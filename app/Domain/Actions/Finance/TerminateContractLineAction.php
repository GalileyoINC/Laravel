<?php

declare(strict_types=1);

namespace App\Domain\Actions\Finance;

use App\Models\Finance\ContractLine;

class TerminateContractLineAction
{
    public function execute(ContractLine $contractLine): void
    {
        $contractLine->terminated_at = now();
        $contractLine->save();
    }
}
