<?php

use App\Http\Controllers\Api\PublicFeedController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PublicFeed API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the PublicFeed module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/public-feed')->group(function () {
    // PublicFeed routes
    Route::post('get-options', [PublicFeedController::class, 'getOptions']);
    Route::post('send', [PublicFeedController::class, 'send']);
    Route::post('image-upload', [PublicFeedController::class, 'imageUpload']);
});
