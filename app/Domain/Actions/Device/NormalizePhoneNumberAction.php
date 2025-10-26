<?php

declare(strict_types=1);

namespace App\Domain\Actions\Device;

use App\Models\Device\PhoneNumber;

final class NormalizePhoneNumberAction
{
    public function execute(PhoneNumber $phone): PhoneNumber
    {
        $normalized = str_replace(['(', ')', '+', '-', '.', ' ', '\\', '/'], '', trim((string) $phone->number));
        $phone->number = $normalized;

        return $phone;
    }
}
