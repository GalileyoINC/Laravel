<?php

declare(strict_types=1);

namespace App\Domain\Actions\Twilio;

use App\Domain\DTOs\Twilio\TwilioIncomingCreateDTO;
use App\Models\System\TwilioIncoming;

final class CreateTwilioIncomingAction
{
    public function execute(TwilioIncomingCreateDTO $dto): TwilioIncoming
    {
        return TwilioIncoming::create([
            'number' => $dto->number,
            'body' => $dto->body,
            'message' => $dto->message ?? '',
        ]);
    }
}
