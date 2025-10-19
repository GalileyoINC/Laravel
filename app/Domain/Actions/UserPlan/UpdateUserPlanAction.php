<?php

declare(strict_types=1);

namespace App\Domain\Actions\UserPlan;

use App\Domain\DTOs\UserPlan\UserPlanUpdateDTO;
use App\Models\User\UserPlan;

final class UpdateUserPlanAction
{
    public function execute(UserPlanUpdateDTO $dto): UserPlan
    {
        $userPlan = UserPlan::findOrFail($dto->id);
        $userPlan->update([
            'end_at' => $dto->endAt,
        ]);

        return $userPlan;
    }
}
