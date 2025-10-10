<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Order API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the Order module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/order')->group(function () {
    // Order routes
    Route::post('create', [OrderController::class, 'create']);
    Route::post('pay', [OrderController::class, 'pay']);
    Route::get('test', [OrderController::class, 'test']);
});
