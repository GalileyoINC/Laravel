<?php

use App\Http\Controllers\Api\NewsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| News API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the News module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/news')->group(function () {
    // News routes
    Route::post('last', [NewsController::class, 'last']);
    Route::post('by-influencers', [NewsController::class, 'byInfluencers']);
    Route::post('by-subscription', [NewsController::class, 'bySubscription']);
    Route::post('set-reaction', [NewsController::class, 'setReaction']);
    Route::post('remove-reaction', [NewsController::class, 'removeReaction']);
});
