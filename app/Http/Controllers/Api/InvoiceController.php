<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Invoice\GetInvoiceAction;
use App\Actions\Invoice\GetInvoiceListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoiceListRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\InvoiceResource;
use Exception;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    public function index(InvoiceListRequest $request, GetInvoiceListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return InvoiceResource::collection($result)->response();
    }

    public function view(int $id, GetInvoiceAction $action): JsonResponse
    {
        $result = $action->execute(['id' => $id]);

        return InvoiceResource::make($result)->response();
    }
}
