<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\EmailPool\DeleteEmailPoolAction;
use App\Domain\Actions\EmailPool\GetEmailAttachmentAction;
use App\Domain\Actions\EmailPool\GetEmailPoolAction;
use App\Domain\Actions\EmailPool\GetEmailPoolListAction;
use App\Domain\Actions\EmailPool\ResendEmailAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailPool\EmailPoolListRequest;
use App\Http\Resources\EmailPoolResource;
use Illuminate\Http\JsonResponse;

class EmailPoolController extends Controller
{
    public function index(EmailPoolListRequest $request, GetEmailPoolListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return EmailPoolResource::collection($result)->response();
    }

    public function view(int $id, GetEmailPoolAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return EmailPoolResource::make($result)->response();
    }

    public function delete(int $id, DeleteEmailPoolAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Email deleted successfully',
        ]);
    }

    public function resend(int $id, ResendEmailAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Email resent successfully',
            'data' => $result,
        ]);
    }

    public function attachment(int $id, GetEmailAttachmentAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
