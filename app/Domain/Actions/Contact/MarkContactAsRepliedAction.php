<?php

declare(strict_types=1);

namespace App\Domain\Actions\Contact;

use App\Models\Communication\Contact;

class MarkContactAsRepliedAction
{
    public function execute(Contact $contact): void
    {
        $contact->update(['status' => 2]);
    }
}
