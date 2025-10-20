<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Models\Device\PhoneNumber;
use Illuminate\Support\Facades\DB;

final class EnsureSingleActiveNumberAction
{
    /**
     * Ensure that for the same phone_number value, only this record is marked as send/valid if it's active.
     * Similar to legacy Yii behavior: reset flags for other rows with the same number.
     */
    public function execute(PhoneNumber $phone): void
    {
        if (! $phone->is_active) {
            return;
        }

        $number = (string) $phone->phone_number;
        if ($number === '') {
            return;
        }

        DB::table('phone_number')
            ->where('phone_number', $number)
            ->where('id', '!=', $phone->id)
            ->update([
                'is_send' => 0,
                'is_valid' => 0,
            ]);
    }
}
