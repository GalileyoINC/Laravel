<?php

use App\Http\Controllers\Api\PhoneController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Phone API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the Phone module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/phone')->group(function () {
    // Phone routes
    Route::post('verify', [PhoneController::class, 'verify']);
    Route::post('set', [PhoneController::class, 'set']);
});
