<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Contact\DeleteContactAction;
use App\Domain\Actions\Contact\GetContactAction;
use App\Domain\Actions\Contact\GetContactListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactListRequest;
use App\Http\Resources\ContactResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Contacts', description: 'Contact management endpoints')]
class ContactController extends Controller
{
    /**
     * Get list of contacts
     *
     * GET /api/contacts
     */
    #[OA\Get(
        path: '/api/v1/contacts',
        description: 'Get paginated list of contacts',
        summary: 'List contacts',
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', description: 'Page number', schema: new OA\Schema(type: 'integer', example: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Items per page', schema: new OA\Schema(type: 'integer', example: 15)),
            new OA\Parameter(name: 'search', in: 'query', description: 'Search term', schema: new OA\Schema(type: 'string', example: 'john')),
        ],
        tags: ['Contacts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Contacts list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'meta', properties: [
                            new OA\Property(property: 'current_page', type: 'integer', example: 1),
                            new OA\Property(property: 'last_page', type: 'integer', example: 5),
                            new OA\Property(property: 'per_page', type: 'integer', example: 15),
                            new OA\Property(property: 'total', type: 'integer', example: 75),
                        ], type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(ContactListRequest $request, GetContactListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return ContactResource::collection($result)->response();
    }

    /**
     * Get single contact
     *
     * GET /api/contacts/{id}
     */
    #[OA\Get(
        path: '/api/v1/contacts/{id}',
        description: 'Get single contact by ID',
        summary: 'Get contact',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'Contact ID', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        tags: ['Contacts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Contact details',
                content: new OA\JsonContent(type: 'object'),
            ),
            new OA\Response(
                response: 404,
                description: 'Contact not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Contact not found'),
                    ]
                )
            ),
        ]
    )]
    public function view(int $id, GetContactAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return ContactResource::make($result)->response();
    }

    /**
     * Delete contact
     *
     * DELETE /api/contacts/{id}
     */
    #[OA\Delete(
        path: '/api/v1/contacts/{id}',
        description: 'Delete contact by ID',
        summary: 'Delete contact',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'Contact ID', schema: new OA\Schema(type: 'integer', example: 1)),
        ],
        tags: ['Contacts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Contact deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Contact marked as deleted successfully'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Contact not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Contact not found'),
                    ]
                )
            ),
        ]
    )]
    public function delete(int $id, DeleteContactAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Contact marked as deleted successfully',
        ]);
    }
}
