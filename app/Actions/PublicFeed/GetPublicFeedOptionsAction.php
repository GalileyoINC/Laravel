<?php

namespace App\Actions\PublicFeed;

use App\DTOs\PublicFeed\PublicFeedOptionsRequestDTO;
use App\Services\PublicFeed\PublicFeedServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetPublicFeedOptionsAction
{
    public function __construct(
        private PublicFeedServiceInterface $publicFeedService
    ) {}

    public function execute(array $data): JsonResponse
    {
        try {
            $dto = PublicFeedOptionsRequestDTO::fromArray($data);
            $user = Auth::user();
            
            $options = $this->publicFeedService->getPublicFeedOptions($dto, $user);

            return response()->json($options);

        } catch (\Exception $e) {
            Log::error('GetPublicFeedOptionsAction error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'An internal server error occurred.',
                'code' => 500
            ], 500);
        }
    }
}
