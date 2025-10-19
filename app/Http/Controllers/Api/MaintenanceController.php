<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Maintenance\SummarizeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\SummarizeRequest;
use Exception;
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
        $result = $this->summarizeAction->execute($request->validated());

        return response()->json([
            'success' => true,
            'data' => [
                'summarized' => $result['summarized'],
            ],
        ]);
    }
}
