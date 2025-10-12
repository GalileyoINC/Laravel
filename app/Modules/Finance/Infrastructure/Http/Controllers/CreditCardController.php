<?php

declare(strict_types=1);

namespace App\Modules\Finance\Infrastructure\Http\Controllers;

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
