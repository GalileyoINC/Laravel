<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\EmailTemplate\GetEmailTemplateAction;
use App\Domain\Actions\EmailTemplate\GetEmailTemplateBodyAction;
use App\Domain\Actions\EmailTemplate\GetEmailTemplateListAction;
use App\Domain\Actions\EmailTemplate\SendAdminEmailAction;
use App\Domain\Actions\EmailTemplate\UpdateEmailTemplateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailTemplate\EmailTemplateListRequest;
use App\Http\Requests\EmailTemplate\EmailTemplateSendRequest;
use App\Http\Requests\EmailTemplate\EmailTemplateUpdateRequest;
use App\Http\Resources\EmailTemplateResource;
use App\Http\Resources\ErrorResource;
use Exception;
use Illuminate\Http\JsonResponse;

class EmailTemplateController extends Controller
{
    public function index(EmailTemplateListRequest $request, GetEmailTemplateListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return EmailTemplateResource::collection($result)->response();
    }

    public function view(int $id, GetEmailTemplateAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return EmailTemplateResource::make($result)->response();
    }

    public function update(int $id, EmailTemplateUpdateRequest $request, UpdateEmailTemplateAction $action): JsonResponse
    {
        $data = array_merge($request->validated(), ['id' => $id]);
        $result = $action->execute($data);

        return EmailTemplateResource::make($result)->response();
    }

    public function viewBody(int $id, GetEmailTemplateBodyAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    public function adminSend(int $id, EmailTemplateSendRequest $request, SendAdminEmailAction $action): JsonResponse
    {
        $data = array_merge($request->validated(), ['id' => $id]);
        $result = $action->execute($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Email sent successfully',
            'data' => $result,
        ]);
    }
}
