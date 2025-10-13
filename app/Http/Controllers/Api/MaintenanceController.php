<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Maintenance\SummarizeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\SummarizeRequest;
use Illuminate\Http\JsonResponse;

class MaintenanceController extends Controller
{
    public function __construct(
        private readonly SummarizeAction $summarizeAction
    ) {}

    /**
     * Summarize text using OpenAI
     */
    public function summarize(SummarizeRequest $request): JsonResponse
    {
        try {
            $result = $this->summarizeAction->execute($request->validated());

            return response()->json([
                'success' => true,
                'data' => [
                    'summarized' => $result['summarized']
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => 500,
                'trace_id' => uniqid(),
            ], 500);
        }
    }
}