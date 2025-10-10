<?php

use App\Http\Controllers\Api\InfluencerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Influencer API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the Influencer module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/influencer')->group(function () {
    // Influencer routes
    Route::post('index', [InfluencerController::class, 'index']);
    Route::post('create', [InfluencerController::class, 'create']);
});
