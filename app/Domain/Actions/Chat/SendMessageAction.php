<?php

declare(strict_types=1);

namespace App\Domain\Actions\Chat;

use App\Domain\DTOs\Chat\SendMessageDTO;
use App\Events\MessageSent;
use App\Models\Communication\ConversationMessage;

class SendMessageAction
{
    public function __construct()
    {
        //
    }

    public function execute(SendMessageDTO $dto): ConversationMessage
    {
        $message = ConversationMessage::create($dto->toArray());

        // Broadcast the event
        event(new MessageSent($message));

        return $message;
    }
}
