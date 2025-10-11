<?php

namespace App\Actions\News;

use App\DTOs\News\ReportNewsRequestDTO;
use App\Services\News\NewsServiceInterface;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportNewsAction
{
    public function __construct(
        private NewsServiceInterface $newsService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = ReportNewsRequestDTO::fromArray($data);
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'code' => 401
                ], 401);
            }

            $result = $this->newsService->reportNews($dto, $user);

            return response()->json(new SuccessResource($result));

        } catch (\Exception $e) {
            Log::error('ReportNewsAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
