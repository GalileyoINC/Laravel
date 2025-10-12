<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\CreditCard\GetCreditCardAction;
use App\Actions\CreditCard\GetCreditCardListAction;
use App\Actions\CreditCard\GetGatewayProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreditCard\CreditCardListRequest;
use App\Http\Resources\CreditCardResource;
use App\Http\Resources\ErrorResource;
use Exception;
use Illuminate\Http\JsonResponse;

class CreditCardController extends Controller
{
    public function index(CreditCardListRequest $request, GetCreditCardListAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return CreditCardResource::collection($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function view(int $id, GetCreditCardAction $action): JsonResponse
    {
        try {
            $result = $action->execute(['id' => $id]);

            return CreditCardResource::make($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function getGatewayProfile(int $id, GetGatewayProfileAction $action): JsonResponse
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
