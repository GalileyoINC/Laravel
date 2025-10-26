<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Actions\Push\SubscribePushAction;
use App\Domain\Actions\Push\UnsubscribePushAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PushController extends Controller
{
    public function __construct(
        private readonly SubscribePushAction $subscribePushAction,
        private readonly UnsubscribePushAction $unsubscribePushAction
    ) {
    }

    public function subscribe(Request $request): JsonResponse
    {
        $subscription = $this->subscribePushAction->execute($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Subscription successful',
            'data' => [
                'id' => $subscription->id,
                'is_active' => $subscription->is_active,
            ],
        ], Response::HTTP_CREATED);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint' => 'required|string',
        ]);

        $success = $this->unsubscribePushAction->execute($request->input('endpoint'));

        if ($success) {
            return response()->json([
                'status' => 'success',
                'message' => 'Unsubscribed successfully',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Subscription not found',
        ], Response::HTTP_NOT_FOUND);
    }
}
