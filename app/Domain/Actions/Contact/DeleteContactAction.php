<?php

declare(strict_types=1);

namespace App\Domain\Actions\Contact;

use App\Domain\Services\Contact\ContactServiceInterface;
use Illuminate\Support\Facades\DB;

class DeleteContactAction
{
    public function __construct(
        private readonly ContactServiceInterface $contactService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): bool
    {
        DB::beginTransaction();

        $this->contactService->markAsDeleted($data['id']);
        DB::commit();

        return true;
    }
}
