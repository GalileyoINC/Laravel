<?php

declare(strict_types=1);

namespace App\Modules\System\Application\Actions\Contact;

use App\Services\Contact\ContactServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DeleteContactAction
{
    public function __construct(
        private readonly ContactServiceInterface $contactService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $this->contactService->markAsDeleted($data['id']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Contact marked as deleted successfully',
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete contact: '.$e->getMessage(),
            ], 500);
        }
    }
}
