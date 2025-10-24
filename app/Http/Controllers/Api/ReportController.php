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
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Reports', description: 'Analytics and reporting operations')]
class ReportController extends Controller
{
    #[OA\Post(
        path: '/api/v1/report/login-statistic',
        description: 'Get login statistics report',
        summary: 'Get login statistics',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'date_from', type: 'string', format: 'date', example: '2024-01-01'),
                    new OA\Property(property: 'date_to', type: 'string', format: 'date', example: '2024-12-31'),
                    new OA\Property(property: 'group_by', type: 'string', example: 'day'),
                ]
            )
        ),
        tags: ['Reports'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login statistics retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function loginStatistic(ReportStatisticRequest $request, GetLoginStatisticAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/v1/report/sold-devices',
        description: 'Get sold devices report',
        summary: 'Get sold devices',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'date_from', type: 'string', format: 'date', example: '2024-01-01'),
                    new OA\Property(property: 'date_to', type: 'string', format: 'date', example: '2024-12-31'),
                    new OA\Property(property: 'device_type', type: 'string', example: 'satellite'),
                ]
            )
        ),
        tags: ['Reports'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Sold devices report retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                    ]
                )
            ),
        ]
    )]
    public function soldDevices(ReportStatisticRequest $request, GetSoldDevicesAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return ReportResource::collection($result)->response();
    }

    #[OA\Post(
        path: '/api/v1/report/influencer-total',
        description: 'Get influencer total report',
        summary: 'Get influencer total',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'date_from', type: 'string', format: 'date', example: '2024-01-01'),
                    new OA\Property(property: 'date_to', type: 'string', format: 'date', example: '2024-12-31'),
                    new OA\Property(property: 'format', type: 'string', example: 'csv'),
                ]
            )
        ),
        tags: ['Reports'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Influencer total report retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function influencerTotal(ReportCsvRequest $request, GetInfluencerTotalAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/v1/report/referral',
        description: 'Get referral report',
        summary: 'Get referral report',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'date_from', type: 'string', format: 'date', example: '2024-01-01'),
                    new OA\Property(property: 'date_to', type: 'string', format: 'date', example: '2024-12-31'),
                    new OA\Property(property: 'format', type: 'string', example: 'csv'),
                ]
            )
        ),
        tags: ['Reports'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Referral report retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function referral(ReportCsvRequest $request, GetReferralReportAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/v1/report/statistic',
        description: 'Get general statistics report',
        summary: 'Get statistics',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'date_from', type: 'string', format: 'date', example: '2024-01-01'),
                    new OA\Property(property: 'date_to', type: 'string', format: 'date', example: '2024-12-31'),
                    new OA\Property(property: 'metric', type: 'string', example: 'users'),
                ]
            )
        ),
        tags: ['Reports'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Statistics retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function statistic(ReportStatisticRequest $request, GetStatisticAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/v1/report/sms',
        description: 'Get SMS report',
        summary: 'Get SMS report',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'date_from', type: 'string', format: 'date', example: '2024-01-01'),
                    new OA\Property(property: 'date_to', type: 'string', format: 'date', example: '2024-12-31'),
                    new OA\Property(property: 'status', type: 'string', example: 'sent'),
                ]
            )
        ),
        tags: ['Reports'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'SMS report retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function sms(ReportStatisticRequest $request, GetSmsReportAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
