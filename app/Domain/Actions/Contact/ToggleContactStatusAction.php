<?php

declare(strict_types=1);

namespace App\Domain\Actions\Contact;

use App\Models\Communication\Contact;

class ToggleContactStatusAction
{
    public function execute(Contact $contact): int
    {
        $newStatus = $contact->status === 1 ? 2 : 1;
        $contact->update(['status' => $newStatus]);

        return $newStatus;
    }
}
