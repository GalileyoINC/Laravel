<?php

declare(strict_types=1);

namespace App\Domain\Actions\Users;

use App\Domain\DTOs\Users\ApplyUserCreditDTO;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class ApplyUserCreditAction
{
    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function execute(ApplyUserCreditDTO $dto): array
    {
        $user = User::findOrFail($dto->userId);

        DB::transaction(function () use ($user, $dto) {
            $user->bonus_point = ($user->bonus_point ?? 0) + (int) $dto->amount;
            $user->save();

            DB::table('user_point_history')->insert([
                'id_user' => $user->id,
                'point' => $dto->amount,
                'reason' => $dto->reason,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return [
            'bonus_point' => $user->bonus_point,
        ];
    }
}
