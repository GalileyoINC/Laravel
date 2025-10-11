<?php

use App\Http\Controllers\Api\CustomerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the Customer module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/customer')->middleware('auth:sanctum')->group(function () {
    // Customer routes
    Route::get('get-profile', [CustomerController::class, 'getProfile']);
    Route::post('update-profile', [CustomerController::class, 'updateProfile']);
    Route::post('change-password', [CustomerController::class, 'changePassword']);
    Route::post('update-privacy', [CustomerController::class, 'updatePrivacy']);
    Route::post('remove-avatar', [CustomerController::class, 'removeAvatar']);
    Route::post('remove-header', [CustomerController::class, 'removeHeader']);
    Route::post('logout', [CustomerController::class, 'logout']);
    Route::post('delete', [CustomerController::class, 'delete']);
});
