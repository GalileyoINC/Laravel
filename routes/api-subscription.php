<?php

use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Subscription API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the Subscription module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/feed')->middleware('auth:sanctum')->group(function () {
    // Subscription routes
    Route::post('set', [SubscriptionController::class, 'set']);
    Route::post('satellite-set', [SubscriptionController::class, 'satelliteSet']);
    Route::get('category', [SubscriptionController::class, 'category']);
    Route::post('index', [SubscriptionController::class, 'index']);
    Route::get('satellite-index', [SubscriptionController::class, 'satelliteIndex']);
    Route::post('add-own-marketstack-indx-subscription', [SubscriptionController::class, 'addOwnMarketstackIndxSubscription']);
    Route::post('add-own-marketstack-ticker-subscription', [SubscriptionController::class, 'addOwnMarketstackTickerSubscription']);
    Route::get('options', [SubscriptionController::class, 'options']);
    Route::post('delete-private-feed', [SubscriptionController::class, 'deletePrivateFeed']);
    Route::get('get-image', [SubscriptionController::class, 'getImage']);
});
