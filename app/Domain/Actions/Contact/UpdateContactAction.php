<?php

declare(strict_types=1);

namespace App\Domain\Actions\Contact;

use App\Models\Communication\Contact;

class UpdateContactAction
{
    public function execute(Contact $contact, array $data): void
    {
        $contact->update($data);
    }
}
