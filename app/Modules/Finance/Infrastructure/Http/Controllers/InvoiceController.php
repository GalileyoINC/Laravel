<?php

declare(strict_types=1);

namespace App\Modules\Finance\Infrastructure\Http\Controllers;

use App\Modules\Finance\Application\Actions\Invoice\GetInvoiceAction;
use App\Modules\Finance\Application\Actions\Invoice\GetInvoiceListAction;
use App\Modules\Finance\Infrastructure\Http\Requests\Invoice\InvoiceListRequest;
use App\Modules\Finance\Infrastructure\Http\Resources\ErrorResource;
use App\Modules\Finance\Infrastructure\Http\Resources\InvoiceResource;
use Exception;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    public function index(InvoiceListRequest $request, GetInvoiceListAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return InvoiceResource::collection($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function view(int $id, GetInvoiceAction $action): JsonResponse
    {
        try {
            $result = $action->execute(['id' => $id]);

            return InvoiceResource::make($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }
}
