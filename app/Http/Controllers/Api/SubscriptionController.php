<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Subscription\SetSubscriptionAction;
use App\Domain\DTOs\Subscription\FeedOptionsDTO;
use App\Domain\DTOs\Subscription\MarketstackSubscriptionDTO;
use App\Domain\Services\Subscription\SubscriptionServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Communication\SmsPoolPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Refactored Subscription Controller using DDD Actions
 * Handles feed subscriptions, categories, and Marketstack subscriptions
 */
class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SetSubscriptionAction $setSubscriptionAction,
        private readonly SubscriptionServiceInterface $subscriptionService
    ) {}

    /**
     * Set subscription status (POST /api/v1/feed/set)
     */
    public function set(Request $request): JsonResponse
    {
        return $this->setSubscriptionAction->execute($request->all());
    }

    /**
     * Set satellite subscription status (POST /api/v1/feed/satellite-set)
     */
    public function satelliteSet(Request $request): JsonResponse
    {
        $data = $request->all();
        $data['sub_type'] = 'satellite';

        return $this->setSubscriptionAction->execute($data);
    }

    /**
     * Get feed categories (GET /api/v1/feed/category)
     */
    public function category(): JsonResponse
    {
        $categories = $this->subscriptionService->getFeedCategories();

        return response()->json($categories);
    }

    /**
     * Get feed list (GET /api/v1/feed/index)
     */
    public function index(Request $request): JsonResponse
    {
        $dto = FeedOptionsDTO::fromRequest($request);
        $user = Auth::user();
        $feeds = $this->subscriptionService->getFeedList($dto, $user);

        return response()->json($feeds);
    }

    /**
     * Get satellite feed list (GET /api/v1/feed/satellite-index)
     */
    public function satelliteIndex(Request $request): JsonResponse
    {
        $dto = FeedOptionsDTO::fromRequest($request);
        $user = Auth::user();
        $feeds = $this->subscriptionService->getSatelliteFeedList($dto, $user);

        return response()->json($feeds);
    }

    /**
     * Add marketstack index subscription (POST /api/v1/feed/add-own-marketstack-indx-subscription)
     */
    public function addOwnMarketstackIndxSubscription(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $data = $request->all();
        $data['type'] = 'indx';
        $dto = MarketstackSubscriptionDTO::fromArray($data);

        if (! $dto->validate()) {
            return response()->json(['errors' => ['Invalid marketstack subscription request']], 400);
        }

        $result = $this->subscriptionService->addMarketstackSubscription($dto, $user);

        return response()->json($result);
    }

    /**
     * Add marketstack ticker subscription (POST /api/v1/feed/add-own-marketstack-ticker-subscription)
     */
    public function addOwnMarketstackTickerSubscription(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $data = $request->all();
        $data['type'] = 'ticker';
        $dto = MarketstackSubscriptionDTO::fromArray($data);

        if (! $dto->validate()) {
            return response()->json(['errors' => ['Invalid marketstack subscription request']], 400);
        }

        $result = $this->subscriptionService->addMarketstackSubscription($dto, $user);

        return response()->json($result);
    }

    /**
     * Get feed options (GET /api/v1/feed/options)
     */
    public function options(): JsonResponse
    {
        $options = $this->subscriptionService->getFeedOptions();

        return response()->json($options);
    }

    /**
     * Delete private feed (POST /api/v1/feed/delete-private-feed)
     */
    public function deletePrivateFeed(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $id = $request->input('id');
        if (! $id) {
            return response()->json(['errors' => ['ID is required']], 400);
        }

        $result = $this->subscriptionService->deletePrivateFeed($id, $user);

        return response()->json($result);
    }

    /**
     * Get image from SMS pool photo (GET /api/v1/feed/get-image)
     */
    public function getImage(Request $request): \Illuminate\Http\Response|JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $id = $request->query('id');
        $type = $request->query('type', 'normal');

        if (! $id) {
            return response()->json(['error' => 'Image ID is required'], 400);
        }

        $smsPoolPhoto = SmsPoolPhoto::find($id);

        if (! $smsPoolPhoto) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Get sizes from JSON column
        $sizes = $smsPoolPhoto->sizes;

        if (empty($sizes) || empty($sizes[$type]['name'])) {
            return response()->json(['error' => 'Image type not found'], 404);
        }

        $filePath = $smsPoolPhoto->folder_name.'/'.$sizes[$type]['name'];

        // Check if file exists in storage
        if (! Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'Image file not found on disk'], 404);
        }

        // Return the file as a response
        return response()->file(
            Storage::disk('public')->path($filePath),
            [
                'Content-Type' => 'image/jpeg',
                'Content-Disposition' => 'inline; filename="'.$sizes[$type]['name'].'"',
            ]
        );
    }
}
