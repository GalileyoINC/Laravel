<?php

declare(strict_types=1);

namespace App\Domain\Actions\Register;

use App\Models\User\Register;

class ToggleUnsubscribeAction
{
    public function execute(Register $register): Register
    {
        $register->is_unsubscribed = ! $register->is_unsubscribed;
        $register->save();

        return $register->fresh();
    }
}
