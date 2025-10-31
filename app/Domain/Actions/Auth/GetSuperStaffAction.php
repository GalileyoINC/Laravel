<?php

declare(strict_types=1);

namespace App\Domain\Actions\Auth;

use App\Models\System\Staff;

class GetSuperStaffAction
{
    public function execute(): ?Staff
    {
        return Staff::find(Staff::ID_SUPER);
    }
}
