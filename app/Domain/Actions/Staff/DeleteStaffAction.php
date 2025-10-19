<?php

declare(strict_types=1);

namespace App\Domain\Actions\Staff;

use App\Models\System\Staff;

class DeleteStaffAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): bool
    {
        /** @var Staff $staff */
        $staff = Staff::findOrFail($data['id']);

        return (bool) $staff->delete();
    }
}
