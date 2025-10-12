<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Infrastructure\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function loginStatistic(ReportStatisticRequest $request, GetLoginStatisticAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function soldDevices(ReportStatisticRequest $request, GetSoldDevicesAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return ReportResource::collection($result)->response();
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function influencerTotal(ReportCsvRequest $request, GetInfluencerTotalAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function referral(ReportCsvRequest $request, GetReferralReportAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function statistic(ReportStatisticRequest $request, GetStatisticAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }

    public function sms(ReportStatisticRequest $request, GetSmsReportAction $action): JsonResponse
    {
        try {
            $result = $action->execute($request->validated());

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return ErrorResource::make($e->getMessage())->response()->setStatusCode(500);
        }
    }
}
