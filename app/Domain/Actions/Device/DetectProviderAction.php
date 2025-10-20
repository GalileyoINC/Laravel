<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Models\Device\PhoneNumber;
use App\Models\Finance\Provider;

final class DetectProviderAction
{
    /**
     * Detect provider for given phone based on number/type using simple regex rules.
     * If a matching Provider name is found (Iridium/Globalstar/Thuraya/Inmarsat), set id_provider.
     */
    public function execute(PhoneNumber $phone): PhoneNumber
    {
        $number = (string) $phone->phone_number;

        // Only attempt for satellite-like types
        if ($phone->type !== PhoneNumber::TYPE_SATELLITE && $phone->type !== PhoneNumber::TYPE_MOBILE) {
            return $phone;
        }

        $name = null;
        // Rules inspired from legacy logic
        if (preg_match('/^(8816|7954)\d*$/', $number)) {
            $name = 'Iridium';
        } elseif (preg_match('/^88216\d{7,8}$/', $number)) {
            $name = 'Thuraya';
        } elseif (preg_match('/^87077\d{7}$/', $number)) {
            $name = 'Inmarsat';
        } else {
            // Fallback
            $name = 'Globalstar';
        }

        $id = Provider::query()->where('name', $name)->value('id');
        if ($id) {
            $phone->id_provider = (int) $id;
        }

        return $phone;
    }
}
