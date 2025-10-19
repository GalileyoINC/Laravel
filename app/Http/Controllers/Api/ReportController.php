<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Report\GetInfluencerTotalAction;
use App\Domain\Actions\Report\GetLoginStatisticAction;
use App\Domain\Actions\Report\GetReferralReportAction;
use App\Domain\Actions\Report\GetSmsReportAction;
use App\Domain\Actions\Report\GetSoldDevicesAction;
use App\Domain\Actions\Report\GetStatisticAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ReportCsvRequest;
use App\Http\Requests\Report\ReportStatisticRequest;
use App\Http\Resources\ReportResource;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function loginStatistic(ReportStatisticRequest $request, GetLoginStatisticAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    public function soldDevices(ReportStatisticRequest $request, GetSoldDevicesAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return ReportResource::collection($result)->response();
    }

    public function influencerTotal(ReportCsvRequest $request, GetInfluencerTotalAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    public function referral(ReportCsvRequest $request, GetReferralReportAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    public function statistic(ReportStatisticRequest $request, GetStatisticAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    public function sms(ReportStatisticRequest $request, GetSmsReportAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
