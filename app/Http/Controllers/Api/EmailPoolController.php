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
use App\Http\Resources\ErrorResource;
use Exception;
use Illuminate\Http\JsonResponse;

class EmailPoolController extends Controller
{
    public function index(EmailPoolListRequest $request, GetEmailPoolListAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return EmailPoolResource::collection($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function view(int $id, GetEmailPoolAction $action): JsonResponse
    {
        try {
            $result = $action->execute(['id' => $id]);

            return EmailPoolResource::make($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function delete(int $id, DeleteEmailPoolAction $action): JsonResponse
    {
        try {
            $result = $action->execute(['id' => $id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Email deleted successfully',
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function resend(int $id, ResendEmailAction $action): JsonResponse
    {
        try {
            $result = $action->execute(['id' => $id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Email resent successfully',
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function attachment(int $id, GetEmailAttachmentAction $action): JsonResponse
    {
        try {
            $result = $action->execute(['id' => $id]);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }
}
