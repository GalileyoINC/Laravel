<?php

use App\Http\Controllers\Api\PrivateFeedController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PrivateFeed API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the PrivateFeed module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/private-feed')->middleware('auth:sanctum')->group(function () {
    // PrivateFeed routes
    Route::get('index', [PrivateFeedController::class, 'index']);
    Route::post('create', [PrivateFeedController::class, 'create']);
    Route::post('update', [PrivateFeedController::class, 'update']);
    Route::post('delete', [PrivateFeedController::class, 'delete']);
});
