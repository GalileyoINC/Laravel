<?php

declare(strict_types=1);

namespace App\Domain\Actions\Contact;

use App\Domain\Services\Contact\ContactServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetContactAction
{
    public function __construct(
        private readonly ContactServiceInterface $contactService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        try {
            $contact = $this->contactService->getById($data['id']);

            return response()->json([
                'status' => 'success',
                'data' => $contact,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get contact: '.$e->getMessage(),
            ], 500);
        }
    }
}
