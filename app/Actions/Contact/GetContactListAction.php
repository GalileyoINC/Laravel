<?php

declare(strict_types=1);

namespace App\Actions\Contact;

use App\DTOs\Contact\ContactListRequestDTO;
use App\Services\Contact\ContactServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetContactListAction
{
    public function __construct(
        private readonly ContactServiceInterface $contactService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = new ContactListRequestDTO(
                page: $data['page'] ?? 1,
                limit: $data['limit'] ?? 20,
                search: $data['search'] ?? null,
                status: $data['status'] ?? 1
            );

            $contacts = $this->contactService->getList($dto);

            return response()->json([
                'status' => 'success',
                'data' => $contacts,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get contact list: '.$e->getMessage(),
            ], 500);
        }
    }
}
