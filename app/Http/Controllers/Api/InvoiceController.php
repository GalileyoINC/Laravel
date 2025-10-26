<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Invoice\GetInvoiceAction;
use App\Domain\Actions\Invoice\GetInvoiceListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoiceListRequest;
use App\Http\Resources\InvoiceResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Invoices', description: 'Invoice management operations')]
class InvoiceController extends Controller
{
    #[OA\Post(
        path: '/api/v1/invoice',
        description: 'Get list of invoices',
        summary: 'List invoices',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page', type: 'integer', example: 1),
                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                    new OA\Property(property: 'search', type: 'string', example: 'invoice search'),
                    new OA\Property(property: 'status', type: 'string', example: 'paid'),
                    new OA\Property(property: 'date_from', type: 'string', format: 'date', example: '2024-01-01'),
                    new OA\Property(property: 'date_to', type: 'string', format: 'date', example: '2024-12-31'),
                ]
            )
        ),
        tags: ['Invoices'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Invoices retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'pagination', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(InvoiceListRequest $request, GetInvoiceListAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return InvoiceResource::collection($result)->response();
    }

    #[OA\Get(
        path: '/api/v1/invoice/{id}',
        description: 'Get specific invoice details',
        summary: 'View invoice',
        tags: ['Invoices'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Invoice ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Invoice retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Invoice not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Invoice not found'),
                    ]
                )
            ),
        ]
    )]
    public function view(int $id, GetInvoiceAction $action): JsonResponse
    {
        $result = $action->execute($id);

        return InvoiceResource::make($result)->response();
    }

    /**
     * Download invoice PDF (POST /api/v1/product/download-invoice)
     */
    public function download(\Illuminate\Http\Request $request)
    {
        $invoiceId = $request->input('invoice_id');

        // Placeholder PDF content (empty). Replace with real file streaming.
        $pdfContent = '%PDF-1.4\n%\xE2\xE3\xCF\xD3\n1 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF';

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice-'.$invoiceId.'.pdf"',
        ]);
    }
}
