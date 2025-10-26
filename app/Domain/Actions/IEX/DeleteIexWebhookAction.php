<?php

declare(strict_types=1);

namespace App\Domain\Actions\IEX;

use App\Models\System\IexWebhook;

final class DeleteIexWebhookAction
{
    public function execute(IexWebhook $webhook): void
    {
        $webhook->delete();
    }
}
