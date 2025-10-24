<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Maintenance\SummarizeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\SummarizeRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Maintenance', description: 'System maintenance and utility operations')]
class MaintenanceController extends Controller
{
    public function __construct(
        private readonly SummarizeAction $summarizeAction
    ) {}

    /**
     * Summarize text using OpenAI
     */
    #[OA\Post(
        path: '/api/v1/maintenance/summarize',
        description: 'Summarize text using AI',
        summary: 'Summarize text',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['text'],
                properties: [
                    new OA\Property(property: 'text', type: 'string', example: 'This is a long text that needs to be summarized into a shorter version while keeping the main points and key information intact.'),
                    new OA\Property(property: 'max_length', type: 'integer', example: 100),
                    new OA\Property(property: 'language', type: 'string', example: 'en'),
                ]
            )
        ),
        tags: ['Maintenance'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Text summarized successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'summarized', type: 'string', example: 'This is a summarized version of the original text with key points preserved.'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'error', type: 'string', example: 'Text is required'),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'AI service error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'error', type: 'string', example: 'AI service temporarily unavailable'),
                    ]
                )
            ),
        ]
    )]
    public function summarize(SummarizeRequest $request): JsonResponse
    {
        $result = $this->summarizeAction->execute($request->validated());

        return response()->json([
            'success' => true,
            'data' => [
                'summarized' => $result['summarized'],
            ],
        ]);
    }
}
