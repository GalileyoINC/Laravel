<?php

declare(strict_types=1);

namespace App\Domain\Actions\Promocode;

use App\Models\Finance\Promocode;

class DeletePromocodeAction
{
    public function execute(Promocode $promocode): void
    {
        $promocode->delete();
    }
}
