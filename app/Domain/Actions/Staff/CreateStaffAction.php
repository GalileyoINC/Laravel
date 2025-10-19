<?php

declare(strict_types=1);

namespace App\Domain\Actions\Staff;

use App\Models\System\Staff;

class CreateStaffAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Staff
    {
        /** @var Staff $staff */
        $staff = Staff::create($data);

        return $staff;
    }
}
