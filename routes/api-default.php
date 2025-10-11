<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Default API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the Default module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/default')->group(function () {
    // Authentication routes
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('news-by-subscription', [AuthController::class, 'newsBySubscription']);
    Route::options('{any}', [AuthController::class, 'options'])->where('any', '.*');
});
