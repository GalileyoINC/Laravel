<?php

declare(strict_types=1);

namespace App\Domain\Actions\Contact;

use App\Domain\Services\Contact\ContactServiceInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class DeleteContactAction
{
    public function __construct(
        private readonly ContactServiceInterface $contactService
    ) {}

    public function execute(array $data): bool
    {
        DB::beginTransaction();

        try {
            $this->contactService->markAsDeleted($data['id']);
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
