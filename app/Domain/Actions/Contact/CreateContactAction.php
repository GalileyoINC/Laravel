<?php

declare(strict_types=1);

namespace App\Domain\Actions\Contact;

use App\Domain\DTOs\Contact\CreateContactDTO;
use App\Domain\Services\Contact\ContactServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class CreateContactAction
{
    public function __construct(
        private readonly ContactServiceInterface $contactService
    ) {}

    public function execute(CreateContactDTO $dto): JsonResponse
    {
        try {
            $contact = $this->contactService->create($dto);

            return response()->json([
                'success' => true,
                'message' => 'Contact created successfully',
                'data' => $contact,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create contact: '.$e->getMessage(),
            ], 500);
        }
    }
}
