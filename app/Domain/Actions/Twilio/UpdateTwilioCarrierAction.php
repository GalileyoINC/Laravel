<?php

declare(strict_types=1);

namespace App\Domain\Actions\Twilio;

use App\Domain\DTOs\Twilio\TwilioCarrierUpdateDTO;
use App\Models\System\TwilioCarrier;

final class UpdateTwilioCarrierAction
{
    public function execute(TwilioCarrierUpdateDTO $dto): bool
    {
        $carrier = TwilioCarrier::findOrFail($dto->carrierId);

        return $carrier->update([
            'provider_id' => $dto->providerId,
        ]);
    }
}
