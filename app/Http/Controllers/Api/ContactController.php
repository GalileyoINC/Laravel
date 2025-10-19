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

class ContactController extends Controller
{
    public function index(ContactListRequest $request, GetContactListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return ContactResource::collection($result)->response();
    }

    public function view(int $id, GetContactAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return ContactResource::make($result)->response();
    }

    public function delete(int $id, DeleteContactAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Contact marked as deleted successfully',
        ]);
    }
}
