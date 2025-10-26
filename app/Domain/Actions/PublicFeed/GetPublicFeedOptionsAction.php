<?php

declare(strict_types=1);

namespace App\Domain\Actions\PublicFeed;

use App\Domain\DTOs\PublicFeed\PublicFeedOptionsRequestDTO;
use App\Domain\Services\PublicFeed\PublicFeedServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetPublicFeedOptionsAction
{
    public function __construct(
        private readonly PublicFeedServiceInterface $publicFeedService
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        $dto = PublicFeedOptionsRequestDTO::fromArray($data);
        $user = Auth::user();

        $options = $this->publicFeedService->getPublicFeedOptions($dto, $user);

        return response()->json($options);
    }
}
