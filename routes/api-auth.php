<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the Authentication module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/auth')->group(function () {
    // Authentication routes
    Route::post('login', [AuthController::class, 'login']);
    Route::get('test', [AuthController::class, 'test']);
    Route::options('{any}', [AuthController::class, 'options'])->where('any', '.*');
});
