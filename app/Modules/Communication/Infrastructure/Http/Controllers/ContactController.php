<?php

declare(strict_types=1);

namespace App\Modules\Communication\Infrastructure\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function index(ContactListRequest $request, GetContactListAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return ContactResource::collection($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function view(int $id, GetContactAction $action): JsonResponse
    {
        try {
            $result = $action->execute(['id' => $id]);

            return ContactResource::make($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function delete(int $id, DeleteContactAction $action): JsonResponse
    {
        try {
            $result = $action->execute(['id' => $id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Contact marked as deleted successfully',
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }
}
