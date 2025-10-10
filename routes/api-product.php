<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Product API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the Product module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/product')->group(function () {
    // Product routes
    Route::post('list', [ProductController::class, 'list']);
    Route::post('alerts', [ProductController::class, 'alerts']);
    Route::post('purchase', [ProductController::class, 'purchase']);
});
